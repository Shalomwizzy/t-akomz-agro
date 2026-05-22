<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index()
    {
        $members = TeamMember::orderBy('sort_order')->get();
        return view('admin.team.index', compact('members'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:150'],
            'role'       => ['required', 'string', 'max:150'],
            'bio'        => ['nullable', 'string', 'max:500'],
            'image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,heic', 'max:8192'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('team', 'public');
        }

        TeamMember::create([
            'name'       => $data['name'],
            'role'       => $data['role'],
            'bio'        => $data['bio'] ?? null,
            'image'      => $imagePath,
            'sort_order' => $data['sort_order'] ?? (TeamMember::max('sort_order') + 1),
            'is_active'  => true,
        ]);

        return back()->with('success', 'Team member added.');
    }

    public function update(Request $request, TeamMember $team)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:150'],
            'role'       => ['required', 'string', 'max:150'],
            'bio'        => ['nullable', 'string', 'max:500'],
            'image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,heic', 'max:8192'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active'  => ['boolean'],
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image if it's in storage (not our-team/ public dir)
            if ($team->image && !str_starts_with($team->image, 'our-team/')) {
                Storage::disk('public')->delete($team->image);
            }
            $data['image'] = $request->file('image')->store('team', 'public');
        } else {
            unset($data['image']);
        }

        $team->update($data);

        return back()->with('success', 'Team member updated.');
    }

    public function destroy(TeamMember $team)
    {
        if ($team->image && !str_starts_with($team->image, 'our-team/')) {
            Storage::disk('public')->delete($team->image);
        }
        $team->delete();

        return back()->with('success', 'Team member removed.');
    }
}
