<?php

namespace App\Infrastructure\Http\Requests\Facultad;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFacultadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'     => 'sometimes|required|string|max:150',
            'codigo'     => 'sometimes|required|string|max:20|unique:facultades,codigo,' . $this->route('id'),
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'filial_id'  => 'sometimes|required|exists:filiales,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.string'   => 'El nombre debe ser texto.',
            'nombre.max'      => 'El nombre no puede superar los 150 caracteres.',
            'codigo.unique'   => 'Ese código ya está en uso.',
            'filial_id.exists' => 'La filial seleccionada no existe.',
        ];
    }
}