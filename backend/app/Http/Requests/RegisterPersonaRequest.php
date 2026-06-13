<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterPersonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => 'required|email|unique:usuario,email',
            'password' => 'required|min:6|confirmed',
            'nombre'   => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'dni'      => 'required|digits:8|unique:persona,dni',
            'telefono' => 'nullable|string|max:15',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'       => 'Este correo ya está registrado.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'dni.digits'         => 'El DNI debe tener exactamente 8 dígitos.',
            'dni.unique'         => 'Este DNI ya está registrado.',
        ];
    }
}