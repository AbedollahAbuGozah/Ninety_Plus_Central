<?php

namespace App\Jobs;

use App\constants\CourseStatusOptions;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ChangeCourseStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Course::where('starts_at', '<=', now())
            ->update([
                'status' => CourseStatusOptions::ACTIVE
            ]);

        Course::doesntHave('students')
            ->where('start_date', '<=', Carbon::now()->subWeek())
            ->update([
                'status' => CourseStatusOptions::CANCELED
            ]);
    }
}
