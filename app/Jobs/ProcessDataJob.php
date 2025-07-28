<?php

namespace App\Jobs;

use App\Models\QueueMonitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\JobStatusUpdated;
use Illuminate\Support\Str;

class ProcessDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


     public $timeout = 300;
    public $tries = 3;

    protected $data;
    protected $monitor;
    /**
     * Create a new job instance.
     */
       public function __construct($data = [])
    {
        $this->data = $data;
        $this->onQueue('default');
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
       $this->monitor = QueueMonitor::create([
            'job_id' => $this->job->getJobId(),
            'name' => 'ProcessDataJob',
            'queue' => $this->queue,
            'started_at' => now(),
            'attempt' => $this->attempts(),
            'data' => $this->data,
            'status' => 'processing'
        ]);

        broadcast(new JobStatusUpdated($this->monitor));

       
        for ($i = 1; $i <= 10; $i++) {
            sleep(2); // Simulate work
            
            $progress = ($i / 10) * 100;
            $this->updateProgress($progress);
            
            // Simulate some processing logic
            $this->processStep($i);
        }

        $this->completeJob();
    }

     protected function updateProgress($progress)
    {
        $this->monitor->update([
            'progress' => $progress,
            'status' => $progress == 100 ? 'completed' : 'processing'
        ]);

        broadcast(new JobStatusUpdated($this->monitor));
    }
     protected function processStep($step)
    {
       
        logger("Processing step {$step} for job {$this->monitor->job_id}");
    }
     protected function completeJob()
    {
        $this->monitor->update([
            'finished_at' => now(),
            'progress' => 100,
            'status' => 'completed'
        ]);

        broadcast(new JobStatusUpdated($this->monitor));
    }

      public function failed(\Throwable $exception)
    {
        if ($this->monitor) {
            $this->monitor->update([
                'failed_at' => now(),
                'exception' => $exception->getMessage(),
                'status' => 'failed'
            ]);

            broadcast(new JobStatusUpdated($this->monitor));
        }
    }
}
