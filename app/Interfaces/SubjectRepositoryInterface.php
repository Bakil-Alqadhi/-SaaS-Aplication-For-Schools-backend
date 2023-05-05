<?php

namespace App\Interfaces;

interface SubjectRepositoryInterface
{
    public function getAllSubjects();
    public function storeSubject($request);
    public function getSubjectById($id);
    public function updateSubject($request, $id);
    // public function getStudentsBySectionId($id);
    public function destroySubject($id);
}