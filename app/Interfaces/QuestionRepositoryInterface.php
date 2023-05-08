<?php

namespace App\Interfaces;

interface QuestionRepositoryInterface
{
    public function getAllQuestions();
    public function storeQuestion($request);
    public function getQuestionById($id);
    public function updateQuestion($request, $id);
    public function destroyQuestion($id);
}