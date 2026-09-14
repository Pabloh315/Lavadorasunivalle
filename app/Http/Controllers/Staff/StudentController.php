<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = User::where('role', 'student')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q')->toString().'%';
                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('last_name', 'like', $term)
                        ->orWhere('student_code', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('campus', 'like', $term);
                });
            })
            ->withCount('reservations')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('staff.students.index', ['students' => $students]);
    }
}
