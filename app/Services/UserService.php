<?php

namespace App\Services;

class UserService extends ApiServices{
    public function __construct(){
        $this->endpoint = env('USERS_MS') . '/api';
    }
}
