<?php

namespace App\Infrastructure\Http\Requests\Ponencia;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePonenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'               => 'sometimes|string|max:255',
            'eventoId'             => 'sometimes|integer|exists:eventos,id',
            'categoriaId'          => 'sometimes|integer|exists:categorias,id',
            'ponente'              => 'sometimes|string|max:255',
            'institucion'          => 'sometimes|nullable|string|max:255',
            'archivoPresentacion'  => 'sometimes|nullable|file|mimes:pdf|max:5120',
            'foto'                 => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
            'codigoQr'             => 'sometimes|string|max:255',
        ];
    }
}