<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * Create a new message instance.
     */
		/*PUBLIC TEM QUE SER DEFINIDO DENTRO DO __CONTRUCT, NÃO FORA, COMO NO TUTORIAL ANTIGO.*/
    public function __construct(public User $user, public string $token)
    {
        $this->user = $user;
				$this->$token = $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Esqueceu sua senha?',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
			return new Content(
				view: 'emails.forgotPassword',
				with: [
				'forgotPassword'=>'http://localhost:5173/reset-password?token=' . $this->token . '&email=' . $this->user->email				]
			
		);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
