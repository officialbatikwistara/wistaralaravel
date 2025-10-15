<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Contoh custom command
Artisan::command('hello', function () {
    $this->info('Hello dari command buatanmu!');
});
