<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('username', 'password');
        $credentials['role'] = 'A';
        $credentials['status'] = 'A';

        $token = Auth::attempt($credentials);

        if(!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau Password salah!'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => Auth::user(),
            'token_type' => 'bearer',
            'token' => $token
        ], 200);
    }

    public function logout(Request $request) {
        Auth::logout();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Logout!'
        ]);
    }
}
