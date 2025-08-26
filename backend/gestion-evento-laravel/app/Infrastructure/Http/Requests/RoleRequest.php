<?php

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Aquí puedes agregar lógica de autorización
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
        ];
    }
}