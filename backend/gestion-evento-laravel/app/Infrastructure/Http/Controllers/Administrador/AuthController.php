<?php

namespace App\Infrastructure\Http\Controllers\Administrador;

use App\Infrastructure\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Eloquent\Models\RoleModel;
use App\Infrastructure\Persistence\Eloquent\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $defaultRole = RoleModel::where('nombre', 'ROLE_USER')->first();

        // Crear usuario asignando el rol por defecto
        $user = UserModel::create([
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

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Iniciar sesión y obtener token",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="admin@ejemplo.com"),
     *             @OA\Property(property="password", type="string", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token generado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJh..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=31536000)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales inválidas"
     *     )
     * )
     */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = UserModel::where('email', $request->email)->with('role')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Crear token
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->accessToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'idUsuario' => $user->id,
                'role'      => $user->role ? $user->role->nombre : null,
                'email'     => $user->email,
            ]
        ]);
    }
}
