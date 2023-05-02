<?php

namespace App\Http\Controllers;

use App\Interfaces\AttendanceRepositoryInterface;
use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{

    private $attendanceRepository;
    public function __construct(Request $request, AttendanceRepositoryInterface $attendanceRepository, AuthRepositoryInterface $authRepositoryInterface)
    {
        $this->attendanceRepository = $attendanceRepository;
        $authRepositoryInterface->switchingMethod($request);
        $this->middleware('auth:sanctum');
    }
    public function show($id)
    {
        // $students = Student::with('attendance')->where('section_id', $id)->get();
    }
}
