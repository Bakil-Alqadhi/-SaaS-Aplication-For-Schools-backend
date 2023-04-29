<?php

namespace App\Interfaces;

interface ClassroomRepositoryInterface
{
    public function getAllClassrooms();
    public function storeClassroom($request);
    public function getClassroomById($id);
    public function updateClassroom($request, $id);
    public function getStudentsByClassroomId($id);
    public function destroyClassroom($id);
}