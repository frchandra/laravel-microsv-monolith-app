<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function compact;


class AuthController extends Controller{

    public  $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request){
        $data = $request->only('first_name', 'last_name', 'email', 'password') + [
                    'is_admin' => $request->path() === 'api/admin/register' ? 1 : 0
                ];

        $user = $this->userService->post("register", $data);

        return response($user, Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
//        if (!\Auth::attempt($request->only('email', 'password'))) {
//            return response([
//                'error' => 'invalid credentials'
//            ], Response::HTTP_UNAUTHORIZED);
//        }

//        $user = \Auth::user();
//
        $adminLogin = $request->path() === 'api/admin/login';
//
//        if ($adminLogin && !$user->is_admin) {
//            return response([
//                'error' => 'Access Denied!'
//            ], Response::HTTP_UNAUTHORIZED);
//        }

        $scope = $adminLogin ? 'admin' : 'ambassador';
        $data = $request->only('email', 'password') + compact('scope');
        $response = $this->userService->post("login", $data); //get a token as a response
//        $token = $user->createToken('token', [$scope])->plainTextToken;

        $cookie = cookie('token', $response['token'], 60 * 24); // 1 day

        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    public function user(Request $request)    {
        $user = $this->userService->get('user');
        return $user;
    }

    public function logout(){
        $cookie = \Cookie::forget('token');

        $this->userService->post('logout', []);

        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    public function updateInfo(UpdateInfoRequest $request){
        $user = $this->userService->put('users/info', $request->only('first_name', 'last_name', 'email'));
        return response($user, Response::HTTP_ACCEPTED);
    }

    public function updatePassword(UpdatePasswordRequest $request){
        $user = $this->userService->put('users/info', $request->only('password'));
        return response($user, Response::HTTP_ACCEPTED); #####
    }
}
