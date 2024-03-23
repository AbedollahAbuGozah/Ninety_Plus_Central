<?php

namespace App\Jobs;

use App\Models\PasswordResetCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteExpiredPasswordResetCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected PasswordResetCode $passwordReset)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->passwordReset->delete();
    }
}
