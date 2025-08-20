<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserFullResource;
use App\Models\User;
use App\Http\Requests\StoreUserFullRequest;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserFullRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserFullController extends Controller
{
    public function show($id)
    {
        $user = User::with(['role', 'persona'])->findOrFail($id);
        return new UserFullResource($user);
    }

    public function index()
    {
        $users = User::with(['role', 'persona'])->get();
        return UserFullResource::collection($users);
    }

    public function store(StoreUserFullRequest $request)
    {

        // Validación
        $validated = $request->validated();

        // Buscar rol
        $role = Role::where('nombre', $validated['role'])->firstOrFail();

        // Crear usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $role->id,
        ]);

        // Manejar imagen
        $imageName = 'default.jpg';
        if ($request->hasFile('fotoPerfil')) {
            // 🔹 Guardar en storage/app/public/personas
            $path = $request->file('fotoPerfil')->store('images/personas', 'public');
            // $path devuelve algo como: "personas/1692552304_foto.jpg"

            $imageName = basename($path); // Solo el nombre del archivo
        }

        // Crear persona
        $user->persona()->create([
            'nombres' => $validated['persona']['nombres'],
            'apellidos' => $validated['persona']['apellidos'],
            'tipoDocumento' => $validated['persona']['tipoDocumento'],
            'numeroDocumento' => $validated['persona']['numeroDocumento'],
            'telefono' => $validated['persona']['telefono'],
            'direccion' => $validated['persona']['direccion'],
            'fechaNacimiento' => $validated['persona']['fechaNacimiento'],
            'correoElectronico' => $validated['email'],
            'fotoPerfil' => $imageName,
            'role_id' => $role->id,
        ]);

        return new UserFullResource($user->load(['role', 'persona']));
    }

    public function update(UpdateUserFullRequest $request, $id)
{
    // Validación
    $data = $request->validated();

    // Buscar usuario con persona
    $user = User::with('persona')->findOrFail($id);

    // --- Actualizar rol ---
    if (isset($data['role'])) {
        $role = Role::where('nombre', $data['role'])->firstOrFail();
        $user->role_id = $role->id;
    }

    // --- Actualizar datos básicos de usuario ---
    if (isset($data['name'])) $user->name = $data['name'];
    if (isset($data['email'])) $user->email = $data['email'];
    if (isset($data['password'])) $user->password = Hash::make($data['password']);
    $user->save();

    // --- Actualizar persona ---
    if ($request->hasAny([
        'persona.nombres',
        'persona.apellidos',
        'persona.tipoDocumento',
        'persona.numeroDocumento',
        'persona.telefono',
        'persona.direccion',
        'persona.fechaNacimiento',
        'fotoPerfil'
    ])) {
        // Tomar solo los campos enviados
        $personaData = $request->only([
            'persona.nombres',
            'persona.apellidos',
            'persona.tipoDocumento',
            'persona.numeroDocumento',
            'persona.telefono',
            'persona.direccion',
            'persona.fechaNacimiento',
        ]);

        // Aplanar los keys tipo persona.nombres → nombres
        $personaData = collect($personaData)
            ->mapWithKeys(function ($value, $key) {
                return [str_replace('persona.', '', $key) => $value];
            })->toArray();

        // Si viene la foto
        if ($request->hasFile('fotoPerfil')) {
            $path = $request->file('fotoPerfil')->store('images/personas', 'public');
            $personaData['fotoPerfil'] = basename($path);
        }

        // Actualizar o crear persona
        if ($user->persona) {
            $user->persona->update($personaData);
        } else {
            $user->persona()->create($personaData);
        }
    }

    return new UserFullResource($user->load(['role', 'persona']));
}

    public function destroy($id)
    {
        $user = User::with('persona')->findOrFail($id);

        if ($user->persona) {
            $user->persona->delete();
        }

        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado correctamente',
        ], 200);
    }
}
