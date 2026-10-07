<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Student;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('attendance');
    }

    public function store(AttendanceRequest $request)
    {
        // Already validated and cleaned by AttendanceRequest
        $data = $request->validated();

        // Authenticate against the database: number AND name must match
        $student = Student::where('student_number', $data['student_number'])
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($data['name'])])
            ->first();

        if (! $student) {
            return back()
                ->withInput()
                ->withErrors(['login' => 'Student number and name do not match our records.']);
        }

        // Record attendance (only once per day)
        $attendance = Attendance::firstOrCreate(
            ['student_id' => $student->id, 'attendance_date' => today()->toDateString()],
            ['time_in' => now()->format('H:i:s')]
        );

        return redirect()->route('attendance.index')->with('student', [
            'student_number' => $student->student_number,
            'name'           => $student->name,
            'photo_url'      => $student->photo ? asset('storage/' . $student->photo) : null,
            'time_in'        => $attendance->time_in,
            'already'        => ! $attendance->wasRecentlyCreated,
        ]);
    }
}