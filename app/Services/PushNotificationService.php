<?php
namespace App\Services;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Contract\Messaging;

class PushNotificationService
{
    protected $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function sendNotification($deviceToken, $title, $body)
    {
        // Crear la notificación
        $notification = Notification::create($title, $body);

        // Crear el mensaje para FCM
        $message = CloudMessage::new()
            ->withNotification($notification)
            ->withTarget('token', $deviceToken);  // Enviar al token específico del dispositivo

        // Enviar la notificación a Firebase
        $this->messaging->send($message);
    }
}
