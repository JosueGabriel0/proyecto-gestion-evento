<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        // Traer todos los usuarios con su rol y persona asociada
        return User::with('role', 'persona')->get();
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'nullable|exists:roles,id',
            'persona_id' => 'nullable|exists:personas,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'persona_id' => $request->persona_id
        ]);

        return response()->json($user, 201);
    }

    public function show($id) {
        return User::with('role', 'persona')->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $data = $request->only(['name','email','role_id','persona_id']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json($user);
    }

    public function destroy($id) {
        User::destroy($id);
        return response()->json(null, 204);
    }
}