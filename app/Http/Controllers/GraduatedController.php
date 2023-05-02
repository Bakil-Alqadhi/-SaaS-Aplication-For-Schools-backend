<?php

namespace App\Http\Controllers;

use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\GraduatedRepositoryInterface;
use Illuminate\Http\Request;

class GraduatedController extends Controller
{
    private $graduatedRepository;
    public function __construct(Request $request, GraduatedRepositoryInterface $graduatedRepository, AuthRepositoryInterface $authRepositoryInterface)
    {
        $this->graduatedRepository = $graduatedRepository;
        $authRepositoryInterface->switchingMethod($request);
        $this->middleware('auth:sanctum');
    }
    public function index()
    {
        return $this->graduatedRepository->index();
    }
    public function store(Request $request)
    {
        return $this->graduatedRepository->softDelete($request);
    }
    public function storeStudent($id)
    {
        return $this->graduatedRepository->softDeleteByStudentId($id);
    }
    public function update($id)
    {
        return $this->graduatedRepository->restoreGraduatedByStudentId($id);
    }
    public function destroy($id)
    {
        return $this->graduatedRepository->destroyGraduatedByStudentId($id);
    }

}