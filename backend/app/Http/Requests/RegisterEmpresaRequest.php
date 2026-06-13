<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'        => 'required|email|unique:usuario,email',
            'password'     => 'required|min:6|confirmed',
            'razon_social' => 'required|string|max:150',
            'ruc'          => 'required|digits:11|unique:empresa,ruc',
            'telefono'     => 'nullable|string|max:15',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'          => 'Este correo ya está registrado.',
            'password.min'          => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed'    => 'Las contraseñas no coinciden.',
            'ruc.digits'            => 'El RUC debe tener exactamente 11 dígitos.',
            'ruc.unique'            => 'Este RUC ya está registrado.',
            'razon_social.required' => 'La razón social es obligatoria.',
        ];
    }
}