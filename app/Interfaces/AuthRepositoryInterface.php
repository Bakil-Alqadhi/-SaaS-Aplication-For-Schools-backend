<?php

namespace App\Interfaces;

interface AuthRepositoryInterface
{

    public function switchingMethod($request);

    //registration method
    public function register($request);

    //login method
    public function login($request);

    //Get Current User
    public function user($request);

    //logout method
    public function logout($request);

}
