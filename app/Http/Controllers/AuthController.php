<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\Chofer;
use App\Models\FuncionarioAlmacen;
use App\Models\GerenteAlmacen;
use App\Models\Administrador;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'rol' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos incorrectos',
                'errors' => $validator->errors()
            ], 400);
        }

        $validatedData = $validator->validated();

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $rolClassName = 'App\Models\\' . $validatedData['rol'];

        if (!class_exists($rolClassName)) {
            return response()->json([
                'message' => 'Rol no válido: ' . $validatedData['rol']
            ], 404);
        }

        $rol = $rolClassName::find($user->ID);
        if (!$rol) {
            return response()->json([
                'message' => 'Usuario no es un ' . $validatedData['rol'] . ' registrado'
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
