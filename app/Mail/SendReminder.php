<?php

namespace App\Mail;

use App\Models\event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public event $event;
    public function __construct(  event $event)
    {
        $this->event = $event;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Reminder',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.send-reminder',
            with: [
                "event_name" => $this->event->title,
                "event_date" => $this->event->date ,
                "event_description" => $this->event->description ,
            ]
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
