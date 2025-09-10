<?php

namespace App\Infrastructure\Http\Requests\Escuela;

use Illuminate\Foundation\Http\FormRequest;

class CreateEscuelaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:150'],
            'codigo' => ['required', 'string', 'max:20', 'unique:escuelas,codigo'],
            'facultad_id' => ['required', 'integer', 'exists:facultades,id'],
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'El código ya está en uso.',
            'facultad_id.required' => 'La facultad es obligatoria.',
            'facultad_id.exists' => 'La facultad seleccionada no existe.',
        ];
    }
}
