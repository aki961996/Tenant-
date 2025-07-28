<?php

namespace Database\Seeders;

use App\Models\QueueMonitor;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QueueMonitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $jobs = [
            // Completed Jobs
            [
                'job_id' => 'job_' . uniqid(),
                'name' => 'ProcessDataJob',
                'queue' => 'default',
                'started_at' => Carbon::now()->subMinutes(15),
                'finished_at' => Carbon::now()->subMinutes(10),
                'failed_at' => null,
                'attempt' => 1,
                'progress' => 100,
                'data' => json_encode(['type' => 'user_export', 'count' => 1500]),
                'exception' => null,
                'status' => 'completed',
                'created_at' => Carbon::now()->subMinutes(16),
                'updated_at' => Carbon::now()->subMinutes(10),
            ],
            [
                'job_id' => 'job_' . uniqid(),
                'name' => 'ProcessDataJob',
                'queue' => 'default',
                'started_at' => Carbon::now()->subMinutes(25),
                'finished_at' => Carbon::now()->subMinutes(20),
                'failed_at' => null,
                'attempt' => 1,
                'progress' => 100,
                'data' => json_encode(['type' => 'email_batch', 'count' => 500]),
                'exception' => null,
                'status' => 'completed',
                'created_at' => Carbon::now()->subMinutes(26),
                'updated_at' => Carbon::now()->subMinutes(20),
            ],
            [
                'job_id' => 'job_' . uniqid(),
                'name' => 'ProcessDataJob',
                'queue' => 'high',
                'started_at' => Carbon::now()->subHour(),
                'finished_at' => Carbon::now()->subMinutes(50),
                'failed_at' => null,
                'attempt' => 1,
                'progress' => 100,
                'data' => json_encode(['type' => 'report_generation', 'count' => 2000]),
                'exception' => null,
                'status' => 'completed',
                'created_at' => Carbon::now()->subHour(1)->subMinutes(5),
                'updated_at' => Carbon::now()->subMinutes(50),
            ],

            // Processing Jobs
            [
                'job_id' => 'job_' . uniqid(),
                'name' => 'ProcessDataJob',
                'queue' => 'default',
                'started_at' => Carbon::now()->subMinutes(5),
                'finished_at' => null,
                'failed_at' => null,
                'attempt' => 1,
                'progress' => 65,
                'data' => json_encode(['type' => 'image_processing', 'count' => 300]),
                'exception' => null,
                'status' => 'processing',
                'created_at' => Carbon::now()->subMinutes(6),
                'updated_at' => Carbon::now()->subMinute(),
            ],
            [
                'job_id' => 'job_' . uniqid(),
                'name' => 'ProcessDataJob',
                'queue' => 'low',
                'started_at' => Carbon::now()->subMinutes(8),
                'finished_at' => null,
                'failed_at' => null,
                'attempt' => 1,
                'progress' => 30,
                'data' => json_encode(['type' => 'backup_process', 'count' => 10000]),
                'exception' => null,
                'status' => 'processing',
                'created_at' => Carbon::now()->subMinutes(9),
                'updated_at' => Carbon::now()->subMinutes(2),
            ],

            // Pending Jobs
            [
                'job_id' => 'job_' . uniqid(),
                'name' => 'ProcessDataJob',
                'queue' => 'default',
                'started_at' => null,
                'finished_at' => null,
                'failed_at' => null,
                'attempt' => 0,
                'progress' => 0,
                'data' => json_encode(['type' => 'data_sync', 'count' => 800]),
                'exception' => null,
                'status' => 'pending',
                'created_at' => Carbon::now()->subMinutes(2),
                'updated_at' => Carbon::now()->subMinutes(2),
            ],
            [
                'job_id' => 'job_' . uniqid(),
                'name' => 'ProcessDataJob',
                'queue' => 'default',
                'started_at' => null,
                'finished_at' => null,
                'failed_at' => null,
                'attempt' => 0,
                'progress' => 0,
                'data' => json_encode(['type' => 'notification_batch', 'count' => 1200]),
                'exception' => null,
                'status' => 'pending',
                'created_at' => Carbon::now()->subMinute(),
                'updated_at' => Carbon::now()->subMinute(),
            ],

            // Failed Jobs
            [
                'job_id' => 'job_' . uniqid(),
                'name' => 'ProcessDataJob',
                'queue' => 'default',
                'started_at' => Carbon::now()->subMinutes(30),
                'finished_at' => null,
                'failed_at' => Carbon::now()->subMinutes(28),
                'attempt' => 3,
                'progress' => 45,
                'data' => json_encode(['type' => 'api_sync', 'count' => 600]),
                'exception' => 'Connection timeout: Unable to connect to external API after 3 attempts',
                'status' => 'failed',
                'created_at' => Carbon::now()->subMinutes(35),
                'updated_at' => Carbon::now()->subMinutes(28),
            ],
            [
                'job_id' => 'job_' . uniqid(),
                'name' => 'ProcessDataJob',
                'queue' => 'high',
                'started_at' => Carbon::now()->subHours(2),
                'finished_at' => null,
                'failed_at' => Carbon::now()->subHours(2)->addMinutes(5),
                'attempt' => 2,
                'progress' => 10,
                'data' => json_encode(['type' => 'file_conversion', 'count' => 50]),
                'exception' => 'Memory limit exceeded: Unable to process large file batch',
                'status' => 'failed',
                'created_at' => Carbon::now()->subHours(2)->subMinutes(2),
                'updated_at' => Carbon::now()->subHours(2)->addMinutes(5),
            ],

            // More variety of completed jobs for better stats
            [
                'job_id' => 'job_' . uniqid(),
                'name' => 'ProcessDataJob',
                'queue' => 'default',
                'started_at' => Carbon::now()->subHours(3),
                'finished_at' => Carbon::now()->subHours(3)->addMinutes(2),
                'failed_at' => null,
                'attempt' => 1,
                'progress' => 100,
                'data' => json_encode(['type' => 'cleanup_task', 'count' => 100]),
                'exception' => null,
                'status' => 'completed',
                'created_at' => Carbon::now()->subHours(3)->subMinutes(1),
                'updated_at' => Carbon::now()->subHours(3)->addMinutes(2),
            ],
            [
                'job_id' => 'job_' . uniqid(),
                'name' => 'ProcessDataJob',
                'queue' => 'low',
                'started_at' => Carbon::now()->subHours(4),
                'finished_at' => Carbon::now()->subHours(4)->addMinutes(15),
                'failed_at' => null,
                'attempt' => 1,
                'progress' => 100,
                'data' => json_encode(['type' => 'analytics_update', 'count' => 5000]),
                'exception' => null,
                'status' => 'completed',
                'created_at' => Carbon::now()->subHours(4)->subMinutes(2),
                'updated_at' => Carbon::now()->subHours(4)->addMinutes(15),
            ],
        ];

        foreach ($jobs as $job) {
            QueueMonitor::create($job);
        }

        $this->command->info('Queue monitor seeded with ' . count($jobs) . ' sample jobs!');
    }
    
}
