<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    public function index() {
        // Traer todas las personas con su rol y usuario asociado
        return Persona::with('role', 'user')->get();
    }

    public function store(Request $request) {
        $persona = Persona::create($request->all());
        return response()->json($persona, 201);
    }

    public function show($id) {
        return Persona::with('role', 'user')->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $persona = Persona::findOrFail($id);
        $persona->update($request->all());
        return response()->json($persona);
    }

    public function destroy($id) {
        Persona::destroy($id);
        return response()->json(null, 204);
    }
}