<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class runrepo implements ShouldQueue
{
    use Queueable, Batchable;

    protected $message;
    /**
     * Create a new job instance.
     */

    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        logger("from runrepo" . $this->message);
    }
}
