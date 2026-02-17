<?php

namespace App\Infrastructure\Http\Requests\Ponencia;

use Illuminate\Foundation\Http\FormRequest;

class CreatePonenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'               => 'required|string|max:255',
            'eventoId'             => 'required|integer|exists:eventos,id',
            'categoriaId'          => 'required|integer|exists:categorias,id',
            'ponente'              => 'required|string|max:255',
            'institucion'          => 'nullable|string|max:255',
            'archivoPresentacion'  => 'nullable|file|mimes:pdf|max:5120', // 5MB
            'foto'                 => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'codigoQr'             => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'      => 'El nombre de la ponencia es obligatorio.',
            'eventoId.required'    => 'Debe seleccionar un evento.',
            'categoriaId.required' => 'Debe seleccionar una categoría.',
            'ponente.required'     => 'El nombre del ponente es obligatorio.',
            'archivoPresentacion.mimes' => 'La presentación debe ser un archivo PDF.',
            'foto.image'           => 'La foto debe ser una imagen válida.',
            'codigoQr.required'    => 'El código QR es obligatorio.',
        ];
    }
}