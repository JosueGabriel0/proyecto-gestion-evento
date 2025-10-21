<?php

namespace App\Infrastructure\Http\Requests\Escuela;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEscuelaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $escuelaId = $this->route('id'); // 👈 asume que tu ruta usa {id}

        return [
            'nombre' => ['sometimes', 'string', 'max:150'],
            'codigo' => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('escuelas', 'codigo')->ignore($escuelaId),
            ],
            'facultad_id' => ['sometimes', 'integer', 'exists:facultades,id'],
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.unique' => 'El código ya está en uso por otra escuela.',
            'facultad_id.required' => 'La facultad es obligatoria.',
            'facultad_id.exists' => 'La facultad seleccionada no existe.',
        ];
    }
}
