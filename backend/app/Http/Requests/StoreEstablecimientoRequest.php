<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEstablecimientoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre'           => 'required|string|max:150',
            'descripcion'      => 'nullable|string',
            'tipo_id'          => 'nullable|exists:tipo_establecimiento,id',
            'tiene_internet'   => 'boolean',
            'precio_entrada'   => 'nullable|numeric|min:0',
            'horario_apertura' => 'nullable|date_format:H:i',
            'horario_cierre'   => 'nullable|date_format:H:i',
            'categorias'       => 'nullable|array',
            'categorias.*'     => 'exists:categoria,id',
            // Ubicación
            'departamento'     => 'nullable|string|max:100',
            'provincia'        => 'nullable|string|max:100',
            'distrito'         => 'nullable|string|max:100',
            'direccion'        => 'nullable|string|max:200',
            'referencia'       => 'nullable|string|max:200',
            'latitud'          => 'nullable|numeric|between:-90,90',
            'longitud'         => 'nullable|numeric|between:-180,180',
        ];
    }
}