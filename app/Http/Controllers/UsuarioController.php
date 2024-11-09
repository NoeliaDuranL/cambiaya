<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        return response()->json(Usuario::all());
    }

    public function show($id)
    {
        $usuario = Usuario::findOrFail($id);
        return response()->json($usuario);
    }

    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'id_persona' => 'required|exists:Persona,id_persona',
            'usuario' => 'required|unique:Usuario,usuario|max:50',
            'correo' => 'required|email|unique:Usuario,correo|max:100',
            'contrasena' => 'required|min:8',
            'estado' => 'nullable|integer'
        ]);

        // Crear el usuario con la contraseña hasheada
        $usuario = Usuario::create([
            'id_persona' => $request->id_persona,
            'usuario' => $request->usuario,
            'correo' => $request->correo,
            'contrasena' => Hash::make($request->contrasena),
            'estado' => $request->estado ?? 1,
        ]);

        return response()->json($usuario, 201);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        // Validar los datos
        $request->validate([
            'id_persona' => 'exists:Persona,id_persona',
            'usuario' => 'unique:Usuario,usuario,' . $id . ',id_usuario|max:50',
            'correo' => 'email|unique:Usuario,correo,' . $id . ',id_usuario|max:100',
            'contrasena' => 'nullable|min:8',
            'estado' => 'nullable|integer'
        ]);

        // Actualizar el usuario, hasheando la contraseña si es proporcionada
        $usuario->update([
            'id_persona' => $request->id_persona ?? $usuario->id_persona,
            'usuario' => $request->usuario ?? $usuario->usuario,
            'correo' => $request->correo ?? $usuario->correo,
            'contrasena' => $request->contrasena ? Hash::make($request->contrasena) : $usuario->contrasena,
            'estado' => $request->estado ?? $usuario->estado,
        ]);

        return response()->json($usuario);
    }

    public function destroy($id)
    {
        Usuario::destroy($id);
        return response()->json(['message' => 'Usuario eliminado con éxito']);
    }
}
