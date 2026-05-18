<?php

namespace App\Jobs;

use App\Mail\ProductosDisponiblesMail;
use App\Models\Producto;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable as FoundationQueueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

final class EnviarProductosDisponiblesJob implements ShouldQueue
{
    use FoundationQueueable;
    use InteractsWithQueue;
    use SerializesModels;

    public function __construct(
        public readonly string $toEmail,
    ) {
    }

    public function handle(): void
    {
        $productos = Producto::query()
            ->with(['categoria', 'marca'])
            ->where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();

        if ($productos->isEmpty()) {
            return;
        }

        Mail::to($this->toEmail)->send(new ProductosDisponiblesMail($productos));
    }
}
