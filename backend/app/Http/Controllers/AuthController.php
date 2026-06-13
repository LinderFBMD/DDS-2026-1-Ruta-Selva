<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterPersonaRequest;
use App\Http\Requests\RegisterEmpresaRequest;
use App\Http\Resources\UsuarioResource;
use App\Models\Usuario;
use App\Models\Persona;
use App\Models\Empresa;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // POST /api/auth/register/persona
    public function registerPersona(RegisterPersonaRequest $request)
    {
        $usuario = Usuario::create([
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'tipo'     => 'persona',
        ]);

        Persona::create([
            'usuario_id' => $usuario->id,
            'nombre'     => $request->nombre,
            'apellido'   => $request->apellido,
            'dni'        => $request->dni,
            'telefono'   => $request->telefono,
        ]);

        $rol = Rol::where('nombre', 'persona')->first();
        if ($rol) $usuario->roles()->attach($rol->id);

        $token = JWTAuth::fromUser($usuario);

        return response()->json([
            'message' => 'Registro exitoso.',
            'token'   => $token,
            'usuario' => new UsuarioResource($usuario->load('persona')),
        ], 201);
    }

    // POST /api/auth/register/empresa
    public function registerEmpresa(RegisterEmpresaRequest $request)
    {
        $usuario = Usuario::create([
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'tipo'     => 'empresa',
        ]);

        Empresa::create([
            'usuario_id'   => $usuario->id,
            'razon_social' => $request->razon_social,
            'ruc'          => $request->ruc,
            'telefono'     => $request->telefono,
        ]);

        $rol = Rol::where('nombre', 'empresa')->first();
        if ($rol) $usuario->roles()->attach($rol->id);

        $token = JWTAuth::fromUser($usuario);

        return response()->json([
            'message' => 'Registro exitoso.',
            'token'   => $token,
            'usuario' => new UsuarioResource($usuario->load('empresa')),
        ], 201);
    }

    // POST /api/auth/login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas.',
            ], 401);
        }

        if (!$usuario->activo) {
            return response()->json([
                'message' => 'Tu cuenta está desactivada.',
            ], 403);
        }

        $token = JWTAuth::fromUser($usuario);
        $relation = $usuario->tipo === 'persona' ? 'persona' : 'empresa';

        return response()->json([
            'message' => 'Inicio de sesión exitoso.',
            'token'   => $token,
            'usuario' => new UsuarioResource($usuario->load($relation)),
        ]);
    }

    // POST /api/auth/logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }

    // GET /api/auth/me
    public function me()
    {
        $usuario  = JWTAuth::parseToken()->authenticate();
        $relation = $usuario->tipo === 'persona' ? 'persona' : 'empresa';

        return response()->json([
            'usuario' => new UsuarioResource($usuario->load($relation)),
        ]);
    }
}