<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    public function login(Request $request) {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);
        if (!Auth::attempt($credentials)) {
            return response(['message' => 'Credenziali non valide'], 422);
        }
        $token = Auth::user()->createToken('admin-token')->plainTextToken;
        return response(['user' => Auth::user(), 'token' => $token]);
    }
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response(['message' => 'Logged out']);
    }
}