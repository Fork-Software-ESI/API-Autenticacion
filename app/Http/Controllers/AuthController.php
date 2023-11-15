<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        if (password_verify($request->password, $user->password)) {
            $token = $user->createToken('auth_token')->accessToken;
            $user->token = $token;
            auth()->login($user);
            return response()->json([
                'message' => 'Login exitoso',
                'user' => $user
            ]);
        }
        return response()->json([
            'message' => 'Contraseña incorrecta'
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        auth()->logout();
        return response()->json([
            'message' => 'Logout exitoso'
        ]);
    }

    public function validarToken(Request $request)
    {
        return auth('api')->user();
    }
}
