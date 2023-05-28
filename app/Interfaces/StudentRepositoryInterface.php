<?php

namespace App\Interfaces;

interface StudentRepositoryInterface
{
    //Get all students
    public function getAllStudent($request);


    //show one student
    public function getStudentById($id);

    //get group's students
    public function getGroupStudents();

    //delete student's account
    public function updateStudent($request, $id);

        //delete student's account
    public function destroyStudentAccount($request, $id);
}