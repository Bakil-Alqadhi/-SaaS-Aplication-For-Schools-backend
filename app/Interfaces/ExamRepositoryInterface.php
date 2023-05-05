<?php

namespace App\Interfaces;

interface ExamRepositoryInterface
{
    public function getAllExams();
    public function storeExam($request);
    public function getExamById($id);
    public function updateExam($request, $id);
    public function destroyExam($id);
}
