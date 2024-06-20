<?php

namespace App\Jobs;

use App\Models\Good;
use App\Models\User;
use App\Services\PushService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SmallRemainderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $good_id;

    /**
     * Create a new job instance.
     */
    public function __construct(int $good_id, public User $user)
    {
        $this->good_id = $good_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $good = Good::find($this->good_id);
        if ($good->amount <= $good->small_remainder) {

            $data = [
                'title' => 'Предупреждение',
                'body' => 'Товар ' . $good->name . ' меньше малого остатка'
            ];

            (new PushService())->send($this->user, $data, $good);
        }
    }
}
