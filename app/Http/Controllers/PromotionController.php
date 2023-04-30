<?php

namespace App\Http\Controllers;

use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\StudentPromotionRepositoryInterface;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    private StudentPromotionRepositoryInterface $promotionRepository;

    public function __construct(Request $request, StudentPromotionRepositoryInterface $promotionRepository, AuthRepositoryInterface $authRepository)
    {
        $authRepository->switchingMethod($request);
        $this->promotionRepository = $promotionRepository;
        $this->middleware('auth:sanctum')->only('store');
    }

    public function store(Request $request)
    {
        return $this->promotionRepository->store($request);
    }
}