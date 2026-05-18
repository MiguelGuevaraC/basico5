<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\EnviarProductosDisponiblesJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('productos:enviar-disponibles', function () {
    $toEmail = (string) env('PRODUCTOS_DISPONIBLES_EMAIL_TO', '');
    if ($toEmail === '') {
        $toEmail = (string) env('MAIL_FROM_ADDRESS', '');
    }

    if ($toEmail === '') {
        $this->error('Debe configurar PRODUCTOS_DISPONIBLES_EMAIL_TO o MAIL_FROM_ADDRESS.');

        return 1;
    }

    EnviarProductosDisponiblesJob::dispatch($toEmail);
    $this->info('Job encolado.');

    return 0;
})->purpose('Encola el envío de correo con productos con stock > 0');

Schedule::command('productos:enviar-disponibles')
    ->dailyAt((string) env('PRODUCTOS_DISPONIBLES_DAILY_AT', '08:00'));
