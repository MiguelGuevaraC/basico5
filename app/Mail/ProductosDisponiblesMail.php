<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

final class ProductosDisponiblesMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Collection $productos,
    ) {
    }

    public function build()
    {
        return $this
            ->subject('Productos disponibles')
            ->view('emails.productos_disponibles', [
                'productos' => $this->productos,
            ]);
    }
}

