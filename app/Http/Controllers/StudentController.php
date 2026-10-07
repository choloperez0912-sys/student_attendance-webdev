<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::withExists([
                'attendances as present_today' => fn ($q) => $q->whereDate('attendance_date', today()),
            ])
            ->orderBy('name')
            ->get();

        return view('teacher.students', compact('students'));
    }

    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();

        // Save the photo as storage/app/public/photos/<student_number>.<ext>
        $path = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $path = $file->storeAs('photos', $data['student_number'] . '.' . $file->extension(), 'public');
        }

        Student::create([
            'student_number' => $data['student_number'],
            'name'           => $data['name'],
            'photo'          => $path,
        ]);

        return redirect()
            ->route('teacher.students.index')
            ->with('status', $data['name'] . ' was registered.');
    }

    public function destroy(Student $student)
    {
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->delete(); // attendance records are removed too (cascade)

        return back()->with('status', 'Student removed.');
    }
}