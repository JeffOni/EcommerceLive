<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class AdminEmailVerificationNotification extends VerifyEmail
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verificación de Email - Panel Administrativo')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Se ha creado una cuenta administrativa para ti en nuestro sistema.')
            ->line('Para completar la configuración de tu cuenta y acceder al panel administrativo, necesitas verificar tu dirección de correo electrónico.')
            ->action('Verificar Email', $verificationUrl)
            ->line('Este enlace de verificación expirará en 60 minutos.')
            ->line('Una vez verificado tu email, podrás acceder al panel administrativo con las credenciales proporcionadas.')
            ->line('Si no solicitaste esta cuenta, puedes ignorar este correo.')
            ->salutation('Saludos,')
            ->salutation('El equipo de ' . config('app.name'));
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60), // Expira en 60 minutos
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
