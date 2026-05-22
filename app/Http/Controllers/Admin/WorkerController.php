<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WorkerWelcome;
use App\Models\Worker;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class WorkerController extends Controller
{
    public function index(Request $request)
    {
        $query = Worker::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('role', 'like', '%' . $request->search . '%');
            });
        }

        $workers = $query->paginate(20)->withQueryString();

        $stats = [
            'total'        => Worker::count(),
            'active'       => Worker::where('status', 'active')->count(),
            'monthly_cost' => Worker::where('status', 'active')->sum('salary_amount'),
            'unpaid'       => Payroll::whereIn('status', ['draft', 'approved'])->count(),
        ];

        return view('admin.workers.index', compact('workers', 'stats'));
    }

    public function create()
    {
        return view('admin.workers.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateWorker($request);
        $data['created_by'] = auth()->id();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('workers', 'public');
        }

        $worker = Worker::create($data);

        if ($worker->email) {
            try { Mail::to($worker->email, $worker->full_name)->send(new WorkerWelcome($worker)); } catch (\Throwable) {}
        }

        return redirect()->route('admin.workers.index')
            ->with('success', 'Worker registered successfully.');
    }

    public function show(Worker $worker)
    {
        $worker->load(['attendances' => fn($q) => $q->latest()->limit(30), 'payrolls.payment']);

        $stats = [
            'total_earned'   => $worker->salaryPayments()->sum('amount_paid'),
            'payrolls_count' => $worker->payrolls()->count(),
            'months_on_farm' => $worker->start_date->diffInMonths(now()) + 1,
            'this_month_att' => $worker->attendances()
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->where('status', 'present')
                ->count(),
        ];

        return view('admin.workers.show', compact('worker', 'stats'));
    }

    public function edit(Worker $worker)
    {
        return view('admin.workers.edit', compact('worker'));
    }

    public function update(Request $request, Worker $worker)
    {
        $data = $this->validateWorker($request, $worker->id);

        if ($request->hasFile('photo')) {
            if ($worker->photo) {
                Storage::disk('public')->delete($worker->photo);
            }
            $data['photo'] = $request->file('photo')->store('workers', 'public');
        }

        $worker->update($data);

        return redirect()->route('admin.workers.show', $worker)
            ->with('success', 'Worker updated successfully.');
    }

    public function destroy(Worker $worker)
    {
        if ($worker->photo) {
            Storage::disk('public')->delete($worker->photo);
        }
        $worker->delete();

        return redirect()->route('admin.workers.index')
            ->with('success', 'Worker removed.');
    }

    private function validateWorker(Request $request, ?int $excludeId = null): array
    {
        return $request->validate([
            'full_name'         => ['required', 'string', 'max:150'],
            'phone'             => ['nullable', 'string', 'max:20'],
            'email'             => ['nullable', 'email', 'max:255'],
            'address'           => ['nullable', 'string', 'max:300'],
            'role'              => ['required', 'string', 'max:80'],
            'employment_type'   => ['required', 'in:daily,weekly,monthly,contract'],
            'salary_type'       => ['required', 'in:fixed,daily_rate,hourly'],
            'salary_amount'     => ['required', 'numeric', 'min:0'],
            'start_date'        => ['required', 'date'],
            'end_date'          => ['nullable', 'date', 'after:start_date'],
            'status'            => ['required', 'in:active,inactive,suspended'],
            'bank_name'         => ['nullable', 'string', 'max:100'],
            'bank_account'      => ['nullable', 'string', 'max:30'],
            'id_number'         => ['nullable', 'string', 'max:50'],
            'emergency_contact' => ['nullable', 'string', 'max:150'],
            'photo'             => ['nullable', 'image', 'max:2048'],
            'notes'             => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
