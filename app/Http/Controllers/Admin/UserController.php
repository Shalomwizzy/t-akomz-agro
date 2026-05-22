<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StaffWelcome;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private array $manageableRoles = ['ADMIN', 'MANAGER', 'SALES', 'CUSTOMER'];
    private array $staffRoles      = ['SUPER_ADMIN', 'ADMIN', 'MANAGER', 'SALES'];

    // Permissions that can be individually toggled per non-admin staff
    public static array $modulePermissions = [
        // Main
        'dashboard.view'        => 'Admin Dashboard',
        // Commerce
        'orders.manage'         => 'Orders',
        'products.manage'       => 'Products',
        'categories.manage'     => 'Categories',
        'inventory.view'        => 'Inventory',
        'promotions.manage'     => 'Promotions',
        'reviews.manage'        => 'Reviews',
        // People
        'customers.view'        => 'Customers',
        'staff.manage'          => 'Staff Management',
        // Content
        'blog.manage'           => 'Blog',
        'gallery.manage'        => 'Gallery',
        'team.manage'           => 'Our Team',
        'tour-bookings.view'    => 'Tour Bookings',
        // Intelligence
        'analytics.view'        => 'Analytics',
        'delivery.manage'       => 'Deliveries',
        // Farm ERP
        'operations.view'       => 'Farm Operations',
        'livestock.manage'      => 'Livestock Batches',
        'farm-supplies.manage'  => 'Farm Supplies',
        // AI
        'ai.access'             => 'AI Assistant',
    ];

    public function index(Request $request)
    {
        $query = User::with('roles')
            ->whereHas('roles', fn($q) => $q->whereIn('name', $this->staffRoles))
            ->latest();

        if ($request->filled('search')) {
            $query->where(fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%")
            );
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }

        $staff               = $query->paginate(25)->withQueryString();
        $roles               = Role::whereIn('name', $this->manageableRoles)->get();
        $staffRolesForFilter = Role::whereIn('name', $this->staffRoles)->get();

        return view('admin.users.index', compact('staff', 'roles', 'staffRolesForFilter'));
    }

    public function create()
    {
        $roles = Role::whereIn('name', $this->manageableRoles)->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', Password::min(8)],
            'role'     => ['required', 'string', 'in:' . implode(',', $this->manageableRoles)],
        ]);

        $this->ensureRoleExists($data['role']);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($data['role']);

        // Send staff welcome email with temporary password
        $staffRoles = ['ADMIN', 'MANAGER', 'SALES'];
        if (in_array($data['role'], $staffRoles)) {
            try {
                Mail::to($user->email)->send(new StaffWelcome($user, $data['password'], $data['role']));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Staff welcome email failed', ['error' => $e->getMessage()]);
            }
        }

        return redirect()->route('admin.users.index')
                         ->with('success', "User {$user->name} created with role {$data['role']}. Welcome email sent.");
    }

    public function edit(User $user)
    {
        $user->load('roles', 'permissions');
        $roles           = Role::whereIn('name', $this->manageableRoles)->get();
        $currentRole     = $user->roles->first()?->name ?? 'CUSTOMER';
        $modulePerms     = self::$modulePermissions;
        $userPermissions = $user->permissions->pluck('name')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'currentRole', 'modulePerms', 'userPermissions'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', Password::min(8)],
            'role'     => ['required', 'string', 'in:' . implode(',', $this->manageableRoles)],
        ]);

        $this->ensureRoleExists($data['role']);

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? $user->phone,
        ]);

        if (!empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        $user->syncRoles([$data['role']]);

        // For non-admin staff, sync any module permissions submitted
        if (in_array($data['role'], ['MANAGER', 'SALES'])) {
            $submitted = $request->input('permissions', []);
            $allowed   = array_keys(self::$modulePermissions);
            $toSync    = array_intersect($submitted, $allowed);

            // Ensure permissions exist before syncing
            foreach ($toSync as $perm) {
                Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
            }

            $user->syncPermissions($toSync);
        } else {
            // ADMIN/SUPER_ADMIN don't need explicit permissions — role covers everything
            $user->syncPermissions([]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->user()?->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted.');
    }

    public function toggle(User $user)
    {
        if ($user->id === auth()->user()?->id) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !($user->is_active ?? true)]);

        return back()->with('success', 'User status updated.');
    }

    private function ensureRoleExists(string $name): void
    {
        Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
    }
}
