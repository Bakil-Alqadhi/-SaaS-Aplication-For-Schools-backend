<?php

namespace App\Http\Controllers;

use App\Http\Resources\GradeResource;
use App\Http\Resources\SectionResource;
use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\SectionRepositoryInterface;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SectionController extends Controller
{
    private SectionRepositoryInterface $sectionRepository;
    public function __construct(Request $request, SectionRepositoryInterface $sectionRepository, AuthRepositoryInterface $authRepositoryInterface)
    {
        $this->sectionRepository = $sectionRepository;

        $authRepositoryInterface->switchingMethod($request);
        $this->middleware('auth:sanctum')->only('store', 'show', 'update', 'addStudents', 'destroy');
    }
    public function index()
    {
        $gradeData = $this->sectionRepository->getSectionsData();
        return response()->json($gradeData, 200);
    }
    public function store(Request $request)
    {
        return $this->sectionRepository->storeSection($request);
    }

    public function show($id)
    {
        return $this->sectionRepository->showSectionById($id);
    }

    public function update(Request $request, $id)
    {
        return $this->sectionRepository->updateSection($request, $id);
    }
    public function addStudents(Request $request, $id)
    {
        return $this->sectionRepository->addStudentsBySectionId($request, $id);
    }
    //getting section's students
    // public function getSectionStudents($id)
    // {
    //     return $this->sectionRepository->getStudentsBySectionId($id);
    // }
    public function destroy($id)
    {
        return $this->sectionRepository->destroySection($id);
    }
}
