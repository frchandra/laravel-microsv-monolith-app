<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use function collect;
use function var_dump;

class AmbassadorController extends Controller{

    public  $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function index(){
        $users = collect($this->userService->get('users'));
        return $users->filter(fn($user) => $user['is_admin'] === 0)->values();
//        return var_dump("hello");
    }
}
