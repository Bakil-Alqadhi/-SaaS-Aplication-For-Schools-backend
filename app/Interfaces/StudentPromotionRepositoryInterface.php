<?php

namespace App\Interfaces;

interface StudentPromotionRepositoryInterface
{
    public function getAllPromotions();
    public function store($request);

    public function destroyAllPromotions($request);
}