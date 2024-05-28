<?php

namespace App\Jobs;

use App\Models\Good;
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
    public function __construct(int $good_id)
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
            //TODO
        }
    }
}
