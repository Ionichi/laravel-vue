<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'me']]);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('username', 'password');
        $credentials['role'] = 'A';
        $credentials['status'] = 'A';

        $token = Auth::attempt($credentials);

        if(!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username atau Password salah!'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil login!',
            'user' => [
                'username' => Auth::user()->username,
                'fullname' => Auth::user()->fullname,
                'gender' => (Auth::user()->gender == 'L') ? 'Laki-laki' :  'Perempuan',
                'email' => Auth::user()->email,
                'role' => (Auth::user()->role == 'A') ? 'Admin' : ((Auth::user()->role == 'T') ? 'Tutor' : 'Murid'),
                'status' => (Auth::user()->status == 'A') ? 'Aktif' : 'Nonaktif',
            ],
            'token_type' => 'Bearer',
            'token' => $token,
        ], 200)->cookie('token', $token, 60);
    }

    public function logout(Request $request) {
        if(Auth::check()) {
            Auth::logout();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil Logout!'
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Tidak ada user yang aktif!',
        ]);
    }

    public function me() {
        if(Auth::check()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data ditemukan!',
                'user' => [
                    'username' => Auth::user()->username,
                    'fullname' => Auth::user()->fullname,
                    'gender' => (Auth::user()->gender == 'L') ? 'Laki-laki' :  'Perempuan',
                    'email' => Auth::user()->email,
                    'role' => (Auth::user()->role == 'A') ? 'Admin' : ((Auth::user()->role == 'T') ? 'Tutor' : 'Murid'),
                    'status' => (Auth::user()->status == 'A') ? 'Aktif' : 'Nonaktif',
                ],
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data tidak ditemukan!',
            'user' => []
        ], 404)->cookie('token');
    }
}
