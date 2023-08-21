<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:55|min:3|regex:/^\S*$/',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Credenciales inválidas',
                'errors' => $validator->errors()
            ], 400);
        }
        $credenciales = $request->only('username', 'password');

        if (Auth::attempt($credenciales)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->accessToken;

            return response()->json([
                'message' => 'Sesión iniciada correctamente',
                'user' => $user,
                'access_token' => $token
            ], 200);
        }

        return response()->json([
            'message' => 'Credenciales incorrectas'
        ], 401);

    }
}