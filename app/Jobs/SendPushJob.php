<?php

namespace App\Jobs;

use App\Models\Document;
use App\Models\User;
use App\Services\PushService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public User $user, public array $data, public Document $document)
    {

    }

    public function handle(): void
    {
        (new PushService())->send($this->user, $this->data, $this->document);
    }
}
