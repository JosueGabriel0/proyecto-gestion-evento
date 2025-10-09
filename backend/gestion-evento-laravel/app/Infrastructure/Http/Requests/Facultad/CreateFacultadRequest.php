<?php

namespace App\Infrastructure\Http\Requests\Facultad;

use Illuminate\Foundation\Http\FormRequest;

class CreateFacultadRequest extends FormRequest
{
    public function authorize(): bool
    {
        // aquí puedes aplicar gates/policies si lo deseas
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'     => 'required|string|max:150',
            'codigo'     => 'required|string|max:20|unique:facultades,codigo',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'filial_id'  => 'required|exists:filiales,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la facultad es obligatorio.',
            'nombre.max'      => 'El nombre no puede superar los 150 caracteres.',
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique'   => 'Ese código ya está registrado.',
            'filial_id.required' => 'La facultad debe pertenecer a una filial.',
            'filial_id.exists'   => 'La filial seleccionada no existe.',
        ];
    }
}