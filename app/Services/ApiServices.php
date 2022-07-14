<?php

namespace App\Services;

use Symfony\Component\HttpKernel\Exception\HttpException;
use function request;

abstract class ApiServices{

    protected string $endpoint;

    public function request($method, $path, $data = []){
        $response = \Http::acceptJson()->withHeaders([
            'Authorization' => 'Bearer ' . request()->cookie('token')
        ])->$method("{$this->endpoint}/{$path}", $data);

        if($response->ok()){
            return $response->json();
        }

        throw new HttpException($response->status(), $response->body());
    }

    public function post($path, $data){
        return $this->request('post', $path, $data);
    }

    public function get($path){
        return $this->request('get', $path);
    }

    public function put($path, $data){
        return $this->request('put', $path, $data);
    }

    public function delete($path){
        return $this->request('delete', $path);
    }
}
