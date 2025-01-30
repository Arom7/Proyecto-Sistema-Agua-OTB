<?php

namespace App\Notifications;

use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnvioCuenta extends Notification
{
    use Queueable;
    protected $data;
    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $data = $this->data;
        $pdf = PDF::loadView('email.pdf_cuenta', ['data' => $data]);
        $pdfOutput = $pdf->output();

        return (new MailMessage)
                    ->subject('Correo de Reporte de Recibo')
                    ->line('Esta notificacion tiene como objetivo enviarle su respectiva cuenta con su usuario y su contraseña correspondiente.')
                    ->line('Ante cualquier falla, contactese con los siguiente numeros, Alan Giovanni Mora Vargas - +591 62615493 (Administrador del sistema).')
                    ->line('Por favor, no responda a este correo. Gracias por usar AQUA CUBE.')
                    ->attachData($pdfOutput, 'Cuenta.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
