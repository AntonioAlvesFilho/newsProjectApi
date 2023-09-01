<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Events\ResetedPassword;
use App\Mail\ResetedPasswordMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendResetedPassword
{
    /**
     * Create the event listener.
     */
		/*PUBLIC TEM QUE SER DEFINIDO DENTRO DO __CONTRUCT, NÃO FORA, COMO NO TUTORIAL ANTIGO.*/
    public function __construct(public User $user)
    {
        $this->user = $user;

    }

    /**
     * Handle the event.
     */
    public function handle(ResetedPassword $event): void
    {
        Mail::to($event->user->email)->send(new ResetedPasswordMail($event->user));
    }
}
