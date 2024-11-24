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
                'numero_celular' => $usuario->numero_celular,
                'nombre' => $usuario->persona->nombre,  // Acceder a los datos de Persona
                'apellido' => $usuario->persona->apellido,  // Acceder a los datos de Persona
                'id_usuario'=> $usuario->id_usuario,
            ]
        ], 200); // Código HTTP 200 para éxito
    }

    // Método para registro
    public function register(Request $request)
    {

        \Log::info('Datos recibidos del registro de google:', $request->all());
        $request->validate([
            'usuario' => 'required|string|unique:usuario',
            'correo' => 'required|email|unique:usuario',
            'contrasena' => 'required|string|min:8',  // Permitir contrasena null
            'numero_celular' => 'nullable|string|max:8', // Permitir numero_celular nulo
            'id_persona' => 'required|exists:persona,id_persona',
        ]);
        \Log::info('Datos validados:', $request->all());

        $usuario = Usuario::create([
            'id_persona' => $request->input('id_persona'),
            'usuario' => $request->input('usuario'),
            'correo' => $request->input('correo'),
            'numero_celular' => $request->input('numero_celular'),
            'contrasena' => Hash::make($request->input('contrasena')),
            'estado' => 1,
        ]);

        $token = $usuario->createToken('MiAplicacion')->plainTextToken;
        // Registrar la respuesta en los logs antes de devolverla
        \Log::info('Respuesta de registro:', [
            'id_usuario' => $usuario->id_usuario,
            'usuario' => $usuario->usuario,
            'correo' => $usuario->correo,
            'numero_celular' => $usuario->numero_celular,
            'token' => $token,
        ]);
        return response()->json([
            'id_usuario' => $usuario->id_usuario,
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


    // Método para obtener el ID de usuario usando el correo
    public function getUserIdByEmail(Request $request)
    {
        // Validar que el correo se haya enviado
        $request->validate([
            'correo' => 'required|email',
        ]);

        // Buscar al usuario por correo
        $usuario = Usuario::with('persona') // Cargar los datos de la persona
                            ->where('correo', $request->correo)
                            ->first();

        // Si no se encuentra el usuario, devolver un error
        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado',
            ], 404); // Código HTTP 404 para "No encontrado"
        }

        // Devolver el ID del usuario
        return response()->json([
            'success' => true,
            'message' => 'Usuario encontrado',
            'id_usuario' => $usuario->id_usuario, // Devolver el ID del usuario
            'usuario' => $usuario->usuario,
            'correo' => $usuario->correo,
            'nombre' => $usuario->persona->nombre,
            'apellido' => $usuario->persona->apellido,
        ], 200); // Código HTTP 200 para éxito
    }

}
