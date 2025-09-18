<?php

namespace App\Infrastructure\Http\Requests\Filial;

use Illuminate\Foundation\Http\FormRequest;

class CreateFilialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Aquí puedes aplicar permisos si quieres
    }

    public function rules(): array
    {
        return [
            'nombre'    => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono'  => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la filial es obligatorio.',
            'nombre.string'   => 'El nombre debe ser texto.',
            'nombre.max'      => 'El nombre no debe superar los 255 caracteres.',
        ];
    }
}