<?php

namespace App\Interfaces;

interface TeacherRepositoryInterface
{
    //register teacher
    public function registerTeacher($request);
    //get all teachers
    public function getAllTeachers();

    //show teacher
    public function getTeacherById($id);
    public function getTeacherSections();

    public function updateTeacher($request, $id);

}