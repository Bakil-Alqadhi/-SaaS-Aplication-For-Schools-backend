<?php

namespace App\Interfaces;

interface QuestionRepositoryInterface
{
    public function getAllQuestions($quiz);
    public function storeQuestion($request, $quiz);
    public function getQuestionById($quiz, $question);
    public function updateQuestion($request, $quiz, $question);
    public function destroyQuestion($quiz, $id);
}