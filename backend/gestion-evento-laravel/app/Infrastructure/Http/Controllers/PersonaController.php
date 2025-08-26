<?php

namespace App\Infrastructure\Http\Controllers;

use App\Infrastructure\Persistence\Eloquent\Models\PersonaModel;
use App\Models\Persona;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    public function index() {
        // Traer todas las personas con su rol y usuario asociado
        return PersonaModel::with('role', 'user')->get();
    }

    public function store(Request $request) {
        $persona = PersonaModel::create($request->all());
        return response()->json($persona, 201);
    }

    public function show($id) {
        return PersonaModel::with('role', 'user')->findOrFail($id);
    }

    public function update(Request $request, $id) {
        $persona = PersonaModel::findOrFail($id);
        $persona->update($request->all());
        return response()->json($persona);
    }

    public function destroy($id) {
        PersonaModel::destroy($id);
        return response()->json(null, 204);
    }
}