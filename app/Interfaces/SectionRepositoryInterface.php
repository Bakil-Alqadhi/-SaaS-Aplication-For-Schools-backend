<?php

namespace App\Interfaces;

interface SectionRepositoryInterface
{
    //get all teachers
    public function getSectionsData();
    public function storeSection($request);
    public function showSectionById($id);
    public function updateSection($request, $id);
    public function addStudentsBySectionId($request, $id);

    public function destroySection($id);

}
