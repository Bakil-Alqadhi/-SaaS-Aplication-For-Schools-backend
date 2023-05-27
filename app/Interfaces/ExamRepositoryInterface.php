<?php

namespace App\Interfaces;

interface ExamRepositoryInterface
{
    public function getAllExams();
    public function getExamQuestionsById($id);
    public function storeAnswersExam($request);

    // public function updateQuestion($request, $quiz, $question);
    // public function destroyQuestion($quiz, $id);
}