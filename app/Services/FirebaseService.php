<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Contract\Messaging;

class FirebaseService
{
    protected $messaging;

    public function __construct(Messaging $messaging)
    {
        // $serviceAccount = json_decode(file_get_contents(storage_path('firebase_credentials.json')), true);
        // $factory = (new Factory)->withServiceAccount($serviceAccount);
        // $this->messaging = $factory->createMessaging();
        $this->messaging = $messaging;
    }

    public function sendNotification($deviceToken, $title, $body)
    {
        // Crea la notificación
        $notification = Notification::create($title, $body);

        // Crea el mensaje a enviar
        $message = CloudMessage::new()
            ->withNotification($notification)
            ->toToken($deviceToken);  // Aquí puedes poner un token de dispositivo o un tema

        try {
            // Enviar el mensaje
            $this->messaging->send($message);
        } catch (\Exception $e) {
            \Log::error('Error al enviar la notificación: '.$e->getMessage());
        }
    }

    public function sendNotificationToTopic($topic, $title, $body)
    {
        // Crea la notificación
        $notification = Notification::create($title, $body);

        // Crea el mensaje para un tema
        $message = CloudMessage::new()
            ->withNotification($notification)
            ->toTopic($topic);

        try {
            // Enviar el mensaje al tema
            $this->messaging->send($message);
        } catch (\Exception $e) {
            \Log::error('Error al enviar la notificación al tema: '.$e->getMessage());
        }
    }
}
