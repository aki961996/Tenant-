<?php

namespace App\Jobs;

use App\Models\QueueMonitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\JobStatusUpdated;
use Illuminate\Support\Facades\Log;

class ProcessDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;
    public $tries = 3;
    public $maxExceptions = 3;

    protected $data;
    protected $monitor;

    public function __construct($data = [])
    {
        $this->data = $data;
        $this->onQueue('default');
    }

    public function handle(): void
    {
        try {
            $this->initializeMonitor();
            
            for ($i = 1; $i <= 10; $i++) {
                $this->processStep($i);
                
                $progress = ($i / 10) * 100;
                $this->updateProgress($progress);
                
                sleep(2); 
            }

            $this->completeJob();
            
        } catch (\Throwable $e) {
            $this->fail($e);
        }
    }

    protected function initializeMonitor(): void
    {
        $this->monitor = QueueMonitor::create([
            'job_id' => $this->job->getJobId(),
            'name' => static::class, 
            'queue' => $this->queue,
            'started_at' => now(),
            'attempt' => $this->attempts(),
            'data' => $this->data,
            'status' => 'processing',
            'progress' => 0
        ]);

        $this->broadcastUpdate();
    }

    protected function updateProgress(float $progress): void
    {
        $this->monitor->update([
            'progress' => $progress,
            'status' => $progress >= 100 ? 'completed' : 'processing',
            'duration' => $this->calculateDuration()
        ]);

        $this->broadcastUpdate();
    }

    protected function processStep(int $step): void
    {
        // Actual processing logic here
        Log::info("Processing step {$step} for job {$this->monitor->job_id}");
    }

    protected function completeJob(): void
    {
        $this->monitor->update([
            'finished_at' => now(),
            'progress' => 100,
            'status' => 'completed',
            'duration' => $this->calculateDuration()
        ]);

        $this->broadcastUpdate();
    }

    protected function calculateDuration(): float
    {
        return $this->monitor->started_at
            ? now()->diffInSeconds($this->monitor->started_at)
            : 0;
    }

    protected function broadcastUpdate(): void
    {
        event(new JobStatusUpdated($this->monitor->fresh()));
    }

    public function failed(\Throwable $exception): void
    {
        if ($this->monitor) {
            $this->monitor->update([
                'failed_at' => now(),
                'exception' => $exception->getMessage(),
                'status' => 'failed',
                'duration' => $this->calculateDuration()
            ]);

            $this->broadcastUpdate();
        }
        
        Log::error("Job failed: " . $exception->getMessage(), [
            'job_id' => $this->monitor?->job_id,
            'exception' => $exception
        ]);
    }
}