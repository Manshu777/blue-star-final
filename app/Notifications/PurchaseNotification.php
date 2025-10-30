<?php

namespace App\Notifications;

use App\Models\Photo;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchaseNotification extends Notification
{
    use Queueable;

    public function __construct(protected Photo $photo, protected $buyer) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Photo Sold! 🎉')
            ->line("Congratulations! Your photo '{$this->photo->title}' was purchased by {$this->buyer->name} for ${$this->photo->price}.")
            ->line('Check your dashboard for earnings.')
            ->action('View Dashboard', url('/photographer/dashboard'))
            ->line('Thank you for contributing to Blue Star Stock!');
    }
}