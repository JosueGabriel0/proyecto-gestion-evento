<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Obtener el rol ROLE_USER
        $defaultRole = \App\Models\Role::where('nombre', 'ROLE_USER')->first();

        // Crear usuario asignando el rol por defecto
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $defaultRole ? $defaultRole->id : null, // asigna null si no existe
        ]);

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'user' => $user
        ]);
    }

    // AuthController.php -> login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Crear token
        $tokenResult = $user->createToken('Personal Access Token');

        // Obtener el token real
        $token = $tokenResult->accessToken;

        // Adjuntar rol al token como extra_info
        $tokenResult->token->save(); // guarda el token

        return response()->json([
            'token' => $token,
            'user' => $user,
            'role' => $user->role->nombre, // incluir rol en la respuesta
        ]);
    }
}
