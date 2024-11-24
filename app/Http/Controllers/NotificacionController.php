<?php
namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Services\PushNotificationService;
use App\Models\Usuario;

class NotificacionController extends Controller
{

    // protected $pushNotificationService;

    // public function __construct(PushNotificationService $pushNotificationService)
    // {
    //     $this->pushNotificationService = $pushNotificationService;
    // }

    // // Enviar la notificación y crear la notificación en la base de datos
    // public function sendNotification(Request $request)
    // {
    //     // Validar los datos recibidos
    //     $request->validate([
    //         'id_usuario' => 'required|exists:usuario,id_usuario',  // Usuario que recibirá la notificación
    //         'id_remitente' => 'required|exists:usuario,id_usuario', // Usuario que está enviando la notificación
    //         'id_post' => 'required|exists:post,id_post',  // El ID del post relacionado (si es necesario)
    //         'token' => 'nullable|string',  // El token opcional que puede ser enviado desde el cliente
    //         'mensaje' => 'required|string',  // El mensaje de la notificación
    //     ]);
    //     // Obtener el token FCM del usuario al que se le enviará la notificación
    //     $usuario = Usuario::find($request->id_usuario);
    //     if (!$usuario) {
    //         return response()->json(['error' => 'Usuario no encontrado'], 404);
    //     }

    //     $fcmToken = $usuario->fcm_token; // Token de FCM almacenado en la base de datos

    //     if (!$fcmToken) {
    //         return response()->json(['error' => 'El usuario no tiene un token FCM'], 400);
    //     }

    //     // Crear el registro de la notificación en la base de datos
    //     $notificacion = Notificacion::create([
    //         'id_usuario' => $request->id_usuario,
    //         'id_remitente' => $request->id_remitente,
    //         'id_post' => $request->id_post,
    //         'token' => $fcmToken,
    //         'mensaje' => $request->mensaje,
    //         'leido' => 0,  // Por defecto, la notificación está no leída
    //     ]);

    //     // Enviar la notificación push al dispositivo del usuario
    //     try {
    //         // Llamamos al servicio para enviar la notificación
    //         $this->pushNotificationService->sendNotification($fcmToken, 'Nueva notificación', $request->mensaje);
    //         return response()->json(['success' => true, 'message' => 'Notificación enviada y almacenada con éxito']);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Error al enviar la notificación', 'message' => $e->getMessage()], 500);
    //     }
    // }

    // protected $firebaseService;

    // public function __construct(FirebaseService $firebaseService)
    // {
    //     $this->firebaseService = $firebaseService;
    // }

    // public function sendToDevice(Request $request)
    // {
    //     $deviceToken = $request->input('token');
    //     $title = $request->input('title');
    //     $body = $request->input('body');

    //     // Llamamos al servicio para enviar la notificación al dispositivo
    //     $this->firebaseService->sendNotification($deviceToken, $title, $body);

    //     return response()->json(['message' => 'Notificación enviada con éxito']);
    // }

    // public function sendToTopic(Request $request)
    // {
    //     $topic = $request->input('topic');
    //     $title = $request->input('title');
    //     $body = $request->input('body');

    //     // Llamamos al servicio para enviar la notificación al tema
    //     $this->firebaseService->sendNotificationToTopic($topic, $title, $body);

    //     return response()->json(['message' => 'Notificación al tema enviada con éxito']);
    // }
    public function index(Request $request)
    {
        // Obtener el id_usuario desde los parámetros de la solicitud
        $id_usuario = $request->input('id_usuario');

        // Si se proporciona un id_usuario, filtrar las notificaciones
        if ($id_usuario) {
            $notificaciones = Notificacion::where('id_remitente', $id_usuario)->get();
        } else {
            // Si no se proporciona id_usuario, se devuelven todas las notificaciones (puedes cambiar esto según lo necesites)
            $notificaciones = Notificacion::all();
        }

        // Devolver la respuesta con el formato adecuado
        return response()->json([
            'success' => true,
            'message' => 'Notificaciones obtenidas con éxito',
            'data' => $notificaciones
        ]);
    }
    // Controlador de Notificación
    public function meInteresa(Request $request)
    {


        \Log::info('Datos recibidos en la solicitud:', $request->all());

        // Validar los datos
        $request->validate([
            'id_usuario' => 'required|exists:usuario,id_usuario',
            'id_remitente' => 'required|exists:usuario,id_usuario',
            'id_post' => 'required|exists:post,id_post',
            'token' => 'nullable|string',
            'mensaje' => 'required|string',
        ]);

        // Crear la notificación
        $notificacion = Notificacion::create([
            'id_usuario' => $request->id_usuario,
            'id_remitente' => $request->id_remitente,
            'id_post' => $request->id_post,
            'token' => $request->token,
            'mensaje' => $request->mensaje,
            'leido' => 0
        ]);

        return response()->json(['success' => true, 'message' => 'Notificación enviada con éxito']);
    }

    public function show($id)
    {
        $notificacion = Notificacion::findOrFail($id);
        // Responder con el formato adecuado
        return response()->json([
            'success' => true,
            'message' => 'Notificación obtenida con éxito',
            'data' => $notificacion
        ]);
    }

    public function store(Request $request)
    {
        // Crear una nueva notificación
        $notificacion = Notificacion::create($request->all());

        // Responder con el formato adecuado
        return response()->json([
            'success' => true,
            'message' => 'Notificación creada con éxito',
            'data' => $notificacion
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $notificacion = Notificacion::findOrFail($id);
        $notificacion->update($request->all());

        // Responder con el formato adecuado
        return response()->json([
            'success' => true,
            'message' => 'Notificación actualizada con éxito',
            'data' => $notificacion
        ]);
    }

    public function destroy($id)
    {
        Notificacion::destroy($id);

        // Responder con el formato adecuado
        return response()->json([
            'success' => true,
            'message' => 'Notificación eliminada con éxito'
        ]);
    }


}

