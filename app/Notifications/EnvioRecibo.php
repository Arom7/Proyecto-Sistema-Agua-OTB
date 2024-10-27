<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnvioRecibo extends Notification
{
    use Queueable;

    protected $recibo;
    /**
     * Create a new notification instance.
     */
    public function __construct($recibo)
    {
        $this->recibo = $recibo;
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
        $data = $this->recibo;

        $pdf = PDF::loadView('email.pdf_recibo', ['datos' => $data]);

        $pdfOutput = $pdf->output();

        return (new MailMessage)
            ->subject('Correo de Reporte de Recibo')
            ->line('Preaviso del mes correspondiente')
            ->line('Gracias por utilizar AquaCube, aca le presento el recibo del presente mes.')
            ->line('Ante cualquier falla, contactese con los siguiente numeros, Sofia Jimenez - +591 ... (Presidente de la zona)  , Santiago Angulo - +591... (Vicepresidente de la zona).')
            ->line('Por favor, no responda a este correo.')
            ->attachData($pdfOutput, 'pre-aviso.pdf', [
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
