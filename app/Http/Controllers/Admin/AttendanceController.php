<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Worker;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date    = $request->date ? \Carbon\Carbon::parse($request->date) : now();
        $workers = Worker::active()->orderBy('full_name')->get();

        // Load existing attendance records for this date
        $records = Attendance::with('worker')
            ->whereDate('date', $date)
            ->get()
            ->keyBy('worker_id');

        // Monthly summary for the attendance overview table
        $month      = $request->month ? \Carbon\Carbon::parse($request->month . '-01') : now()->startOfMonth();
        $monthEnd   = $month->copy()->endOfMonth();
        $monthDays  = $month->diffInWeekdays($monthEnd) + 1;

        $monthlySummary = Worker::active()->get()->map(function ($worker) use ($month, $monthEnd, $monthDays) {
            $present  = $worker->daysPresent($month, $monthEnd);
            $halfDays = $worker->halfDays($month, $monthEnd);
            $effective = $present + ($halfDays * 0.5);
            return [
                'worker'    => $worker,
                'present'   => $present,
                'half_days' => $halfDays,
                'absent'    => $monthDays - $present - $halfDays,
                'rate'      => $monthDays > 0 ? round(($effective / $monthDays) * 100) : 0,
            ];
        });

        return view('admin.attendance.index', compact('date', 'workers', 'records', 'month', 'monthlySummary'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'date'           => ['required', 'date'],
            'attendance'     => ['required', 'array'],
            'attendance.*.status' => ['required', 'in:present,absent,half_day,holiday,leave'],
        ]);

        $date    = $request->date;
        $adminId = auth()->id();

        foreach ($request->attendance as $workerId => $entry) {
            Attendance::updateOrCreate(
                ['worker_id' => $workerId, 'date' => $date],
                [
                    'status'        => $entry['status'],
                    'check_in_time' => $entry['check_in'] ?? null,
                    'check_out_time'=> $entry['check_out'] ?? null,
                    'notes'         => $entry['notes'] ?? null,
                    'recorded_by'   => $adminId,
                ]
            );
        }

        return back()->with('success', 'Attendance saved for ' . \Carbon\Carbon::parse($date)->format('M d, Y') . '.');
    }

    public function update(Request $request, Attendance $attendance)
    {
        $data = $request->validate([
            'status'         => ['required', 'in:present,absent,half_day,holiday,leave'],
            'check_in_time'  => ['nullable', 'date_format:H:i'],
            'check_out_time' => ['nullable', 'date_format:H:i'],
            'notes'          => ['nullable', 'string', 'max:300'],
        ]);

        $attendance->update($data + ['recorded_by' => auth()->id()]);

        return back()->with('success', 'Attendance updated.');
    }
}
