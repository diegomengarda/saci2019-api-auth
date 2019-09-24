<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return response($students);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $student = new Student();
        $student->fill($data);
        $student->save();
        return response($student);
    }

    public function show(int $studentId)
    {
        $student = Student::find($studentId);
        if (!$student) {
            return response(['error' => 'Student not found'], 400);
        }
        return response($student);
    }

    public function update(Request $request, int $studentId)
    {
        $student = Student::find($studentId);
        if (!$student) {
            return response(['error' => 'Student not found'], 400);
        }

        $data = $request->all();
        $student->fill($data);
        $student->save();

        return response($student);
    }

    public function destroy(int $studentId)
    {
        $student = Student::find($studentId);
        if (!$student) {
            return response(['error' => 'Student not found'], 400);
        }
        $student->delete();
        return response(['message' => 'Student deleted']);
    }
}
