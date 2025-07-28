<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueMonitor extends Model
{
    use HasFactory;

    protected $table = 'queue_monitor';

     protected $fillable = [
        'job_id',
        'name',
        'queue',
        'started_at',
        'finished_at',
        'failed_at',
        'attempt',
        'progress',
        'data',
        'exception',
        'status'
    ];
      protected $casts = [
        'data' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'failed_at' => 'datetime',
    ];
      public function getDurationAttribute()
    {
        if ($this->started_at && $this->finished_at) {
            return $this->started_at->diffInSeconds($this->finished_at);
        }
        return null;
    }

     public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'processing' => 'blue',
            'completed' => 'green',
            'failed' => 'red',
            default => 'gray'
        };
    }

}
