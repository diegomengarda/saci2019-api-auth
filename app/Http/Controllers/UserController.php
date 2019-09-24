<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response(['error' => 'Login ou Senha incorretos'], 401);
        }

        return response(['token' => $token]);
    }

    public function userLogged()
    {
        $user = auth('api')->user();
        return response($user);
    }
}
