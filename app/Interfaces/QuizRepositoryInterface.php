<?php

namespace App\Interfaces;

interface QuizRepositoryInterface
{
    public function getAllQuizzes();
    public function storeQuiz($request);
    public function getQuizById($id);
    public function updateQuiz($request, $id);
    public function destroyQuiz($id);
}