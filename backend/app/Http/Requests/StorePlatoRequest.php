<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlatoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio'      => 'nullable|numeric|min:0',
            'foto_url'    => 'nullable|url',
            'disponible'  => 'boolean',
        ];
    }
}