<?php

namespace App\Infrastructure\Http\Requests\Filial;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFilialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'    => 'sometimes|string|max:255',
            'direccion' => 'sometimes|nullable|string|max:255',
            'telefono'  => 'sometimes|nullable|string|max:20',
            'email'     => 'sometimes|nullable|email|max:255',
            'foto'      => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}