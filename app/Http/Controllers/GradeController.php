<?php

namespace App\Http\Controllers;

use App\Http\Resources\GradeResource;
use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\GradeRepositoryInterface;
use App\Models\Grade;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use function PHPSTORM_META\type;

class GradeController extends Controller
{
    private GradeRepositoryInterface $gradeRepository;
    public function __construct(Request $request, GradeRepositoryInterface $gradeRepository, AuthRepositoryInterface $authRepositoryInterface)
    {
        $this->gradeRepository = $gradeRepository;
        $authRepositoryInterface->switchingMethod($request);

    }
    public function index()
    {
        return $this->gradeRepository->getAllGrades();
    }

    public function gradeData()
    {
        return $this->gradeRepository->getGradeData();
    }

    public function store(Request $request)
    {
        return $this->gradeRepository->storeGrade($request);
    }
    public function show($id)
    {
       return $this->gradeRepository->showGrade($id);
    }

    public function update(Request $request, $id)
    {
        return $this->gradeRepository->updateGrade($request, $id);
    }
    public function destroy($id)
    {
        return $this->gradeRepository->destroyGrade($id);
    }
}
