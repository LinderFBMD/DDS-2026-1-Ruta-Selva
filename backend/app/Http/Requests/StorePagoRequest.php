<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePagoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'suscripcion_id' => 'required|exists:suscripcion,id',
            'metodo_pago'    => 'required|in:yape,plin,tarjeta,transferencia',
            'referencia'     => 'nullable|string|max:100',
        ];
    }
}