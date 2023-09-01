<?php

namespace App\Listeners;

use App\Events\ForgotPassword;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendForgotPassword
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ForgotPassword $event): void
    {
			Mail::to($event->user->email)->send(new ForgotPasswordMail($event->user, $event->token));
		}
}