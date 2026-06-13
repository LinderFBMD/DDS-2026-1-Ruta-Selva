<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreComentarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo usuarios autenticados y de tipo 'persona' pueden comentar
        $usuario = $this->user();
        return $usuario && $usuario->tipo === 'persona';
    }

    public function rules(): array
    {
        return [
            'establecimiento_id' => [
                'required',
                'integer',
                'exists:establecimiento,id',
                // Evita que un mismo usuario comente el mismo establecimiento dos veces
                Rule::unique('comentario')->where(function ($query) {
                    return $query->where('usuario_id', $this->user()->id)
                                 ->where('establecimiento_id', $this->establecimiento_id);
                })
            ],
            'texto' => 'required|string|min:5|max:1000',
            'estrellas' => 'required|integer|min:1|max:5',
        ];
    }

    public function messages(): array
    {
        return [
            'establecimiento_id.unique' => 'Ya has dejado un comentario en este establecimiento.',
            'texto.required' => 'El comentario no puede estar vacío.',
            'texto.min' => 'El comentario debe tener al menos 5 caracteres.',
            'estrellas.required' => 'Debes seleccionar una calificación.',
            'estrellas.min' => 'La calificación mínima es 1 estrella.',
            'estrellas.max' => 'La calificación máxima es 5 estrellas.',
        ];
    }
}