<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmailBase
{
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Verifikasi Email Akun Batik Wistara')
            ->view('emails.verify', ['url' => $url]);
    }
}
