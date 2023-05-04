<?php

namespace App\Interfaces;

interface AttendanceRepositoryInterface
{
    public function getStudentsBySectionId($id);
    public function storeAttendance($request, $id);
}