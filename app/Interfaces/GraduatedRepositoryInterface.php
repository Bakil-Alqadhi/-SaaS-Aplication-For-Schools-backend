<?php

namespace App\Interfaces;

interface GraduatedRepositoryInterface
{
    public function index();
    public function softDelete($request);
    public function restoreGraduatedByStudentId($id);
    public function softDeleteByStudentId($id);
    public function destroyGraduatedByStudentId($id);
}