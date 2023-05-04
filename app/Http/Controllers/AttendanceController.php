<?php

namespace App\Http\Controllers;

use App\Interfaces\AttendanceRepositoryInterface;
use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{

    private AttendanceRepositoryInterface $attendanceRepository;
    public function __construct(Request $request, AttendanceRepositoryInterface $attendanceRepository, AuthRepositoryInterface $authRepositoryInterface)
    {
        $this->attendanceRepository = $attendanceRepository;
        $authRepositoryInterface->switchingMethod($request);
        $this->middleware('auth:sanctum')->only('show', 'store');
    }

    //getting section's students
    public function show($id)
    {
        // $students = Student::with('attendance')->where('section_id', $id)->get();
        return $this->attendanceRepository->getStudentsBySectionId($id);
    }
    public function store(Request $request, $id)
    {
        return $this->attendanceRepository->storeAttendance($request, $id);
    }
}