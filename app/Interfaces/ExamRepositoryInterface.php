<?php

namespace App\Interfaces;

interface ExamRepositoryInterface
{
    public function getAllExams();
    // public function storeExam($request, $quiz);
    public function getExamQuestionsById($id);
    // public function updateQuestion($request, $quiz, $question);
    // public function destroyQuestion($quiz, $id);
}