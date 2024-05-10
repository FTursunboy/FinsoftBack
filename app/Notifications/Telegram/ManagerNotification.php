<?php

namespace App\Notifications\Telegram;

use App\Models\OrderDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class ManagerNotification extends Notification
{
    use Queueable;

    public function __construct(OrderDocument $document)
    {
        $this->document = $document;
    }

    public function via(object $notifiable): array
    {
        return [TelegramChannel::class];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to($notifiable->telegram_chat_id)
            ->content(
                "Заказ #{$this->document->doc_number}
                \n{$this->document->date}
                "
            );
    }
}
