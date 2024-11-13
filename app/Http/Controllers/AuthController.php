<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
            throw ValidationException::withMessages([
                'usuario' => ['Credenciales incorrectas'],
            ]);
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
            ]
        ]);
    }

    // Método para registro
    public function register(Request $request)
    {
        // Validación de los datos que se reciben en el registro
        $request->validate([
            'usuario' => 'required|string|unique:usuario',
            'correo' => 'required|email|unique:usuario',
            'contrasena' => 'required|string|min:6',
            'id_persona' => 'required|exists:persona,id_persona', // Validación de id_persona
        ]);

        // Crear un nuevo usuario
        $usuario = Usuario::create([
            'id_persona' => $request->id_persona,  // Asignación del id_persona
            'usuario' => $request->usuario,
            'correo' => $request->correo,
            'contrasena' => Hash::make($request->contrasena),  // Asegúrate de hashear la contraseña
            'estado' => 1,  // Si el estado por defecto es 'S' (activo)
        ]);

        // Generar un token de acceso para el nuevo usuario
        $token = $usuario->createToken('MiAplicacion')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registro exitoso',
            'token' => $token,
        ]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
        ]);

        // Obtener el usuario autenticado
        $usuario = auth()->user();

        // Actualizar los datos de la persona
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
        ]);
    }


}
