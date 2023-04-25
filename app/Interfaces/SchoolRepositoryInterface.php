<?php

namespace App\Interfaces;

interface SchoolRepositoryInterface
{
    //register teacher
    public function getWaiting();
    //get all teachers
    public function newMember($_request, $id);

    //show teacher
    public function getTeacherById($id);

    public function updateTeacher($request, $id);
}