<?php

namespace App\Interfaces;

interface GradeRepositoryInterface
{
    //get all teachers
    public function getAllGrades();
    public function getGradeDAta();
    public function storeGrade($request);
    public function showGrade($id);
    public function updateGrade($request, $id);

    public function destroyGrade($id);
}