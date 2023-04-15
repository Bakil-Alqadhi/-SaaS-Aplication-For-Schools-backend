<?php

namespace App\Http\Controllers;

use App\Http\Resources\GradeResource;
use App\Http\Resources\SectionResource;
use App\Interfaces\SectionRepositoryInterface;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SectionController extends Controller
{
    private SectionRepositoryInterface $sectionRepository;
    public function __construct(SectionRepositoryInterface $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
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
        return $this->sectionRepository->showSection($id);
    }

    public function update(Request $request, $id)
    {
        return $this->sectionRepository->updateSection($request, $id);
    }
    public function destroy($id)
    {
        return $this->sectionRepository->destroySection($id);
    }
}