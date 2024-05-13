<?php

namespace App\Jobs\Telegram;

use App\Models\OrderDocument;
use App\Notifications\Telegram\ManagerNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class ManagerNotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private OrderDocument $document;

    /**
     * Create a new job instance.
     */
    public function __construct(OrderDocument $document)
    {
        $this->document = $document;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (auth()->user()->telegram_chat_id !== null){
            Notification::send(auth()->user(), new ManagerNotification($this->document));
        }
    }
}
