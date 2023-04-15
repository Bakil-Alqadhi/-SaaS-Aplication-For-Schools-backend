<?php

namespace App\Interfaces;

interface SectionRepositoryInterface
{
    //get all teachers
    public function getSectionsData();
    public function storeSection($request);
    public function showSection($id);
    public function updateSection($request, $id);

    public function destroySection($id);

}