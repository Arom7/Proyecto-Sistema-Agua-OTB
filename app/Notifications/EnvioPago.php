<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class EnvioPago extends Notification
{
    use Queueable;
    protected $recibo_pago;
    /**
     * Create a new notification instance.
     */
    public function __construct($recibo_pago)
    {
        $this->recibo_pago = $recibo_pago;
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
        $data = $this->recibo_pago;

        $pdf = PDF::loadView('email.pdf_pago', ['datos' => $data]);

        $pdfOutput = $pdf->output();

        return (new MailMessage)
                    ->line('Envio de notificacion de pagos. Su pago a sido registrado con exito en el sistema.')
                    ->line('Gracias por utilizar Aqua-Cube, aca le presento el recibo de pago.')
                    ->line('No es necesario que necesario que responda a este correo!')
                    ->attachData($pdfOutput, 'Copia-Pago.pdf', [
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
