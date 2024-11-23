<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Método para login
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'contrasena' => 'required|string',
        ]);

        // Buscar el usuario en la base de datos y cargar los datos de la persona
        $usuario = Usuario::with('persona') // Eager load de la relación persona
                        ->where('usuario', $request->usuario)
                        ->first();

        // Verificar que el usuario exista y la contraseña coincida
        if (!$usuario || !Hash::check($request->contrasena, $usuario->contrasena)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas',
            ], 401); // Código HTTP 401 para "No autorizado"
        }

        // Generar el token
        $token = $usuario->createToken('MiAplicacion')->plainTextToken;

        // Devolver la respuesta con los datos del usuario y de la persona
        return response()->json([
            'success' => true,
            'message' => 'Login exitoso',
            'token' => $token,
            'user' => [
                'usuario' => $usuario->usuario,
                'correo' => $usuario->correo,
                'nombre' => $usuario->persona->nombre,  // Acceder a los datos de Persona
                'apellido' => $usuario->persona->apellido,  // Acceder a los datos de Persona
                'id_usuario'=> $usuario->id_usuario,
            ]
        ], 200); // Código HTTP 200 para éxito
    }

    // Método para registro
    public function register(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|unique:usuario',
            'correo' => 'required|email|unique:usuario',
            'contrasena' => 'required|string|min:6',
            'id_persona' => 'required|exists:persona,id_persona',
        ]);

        $usuario = Usuario::create([
            'id_persona' => $request->id_persona,
            'usuario' => $request->usuario,
            'correo' => $request->correo,
            'contrasena' => Hash::make($request->contrasena),
            'estado' => 1,
        ]);

        $token = $usuario->createToken('MiAplicacion')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registro exitoso',
            'token' => $token,
        ], 201); // Código HTTP 201 para "Recurso creado"
    }

    // Método para actualizar datos
    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
        ]);

        $usuario = auth()->user();

        $usuario->persona->nombre = $request->nombre;
        $usuario->persona->apellido = $request->apellido;
        $usuario->persona->save();

        return response()->json([
            'success' => true,
            'message' => 'Datos actualizados correctamente',
            'user' => [
                'nombre' => $usuario->persona->nombre,
                'apellido' => $usuario->persona->apellido,
            ]
        ], 200); // Código HTTP 200 para éxito
    }
}
