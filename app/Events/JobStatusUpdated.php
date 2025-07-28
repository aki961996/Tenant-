<?php

namespace App\Events;

use App\Models\QueueMonitor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

     public $queueMonitor;
    /**
     * Create a new event instance.
     */
    public function __construct(QueueMonitor $queueMonitor)
    {
        $this->queueMonitor = $queueMonitor;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
   public function broadcastOn()
    {
        return new Channel('queue-monitor');
    }

    public function broadcastAs()
    {
        return 'job.updated';
    }
    public function broadcastWith()
    {
        return [
            'job' => [
                'id' => $this->queueMonitor->id,
                'job_id' => $this->queueMonitor->job_id,
                'name' => $this->queueMonitor->name,
                'queue' => $this->queueMonitor->queue,
                'status' => $this->queueMonitor->status,
                'progress' => $this->queueMonitor->progress,
                'started_at' => $this->queueMonitor->started_at?->toISOString(),
                'finished_at' => $this->queueMonitor->finished_at?->toISOString(),
                'failed_at' => $this->queueMonitor->failed_at?->toISOString(),
                'duration' => $this->queueMonitor->duration,
                'attempt' => $this->queueMonitor->attempt,
                'exception' => $this->queueMonitor->exception,
                'status_color' => $this->queueMonitor->status_color,
            ]
        ];
    }

}
