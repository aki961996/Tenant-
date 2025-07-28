<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessDataJob;
use App\Models\QueueMonitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;

class QueueMonitorController extends Controller
{
     public function index()
    {
        $jobs = QueueMonitor::orderBy('created_at', 'desc')->paginate(20);
        $stats = $this->getQueueStats();
        
        return view('queue-monitor.index', compact('jobs', 'stats'));
    }

    public function show(QueueMonitor $queueMonitor)
    {
    //  dd($queueMonitor);
        return view('queue-monitor.show', compact('queueMonitor'));
    }

    public function dispatch(Request $request)
    {
        $data = $request->input('data', ['sample' => 'data']);
        
        ProcessDataJob::dispatch($data);
        
        return redirect()->back()->with('success', 'Job dispatched successfully!');
    }

    public function clear()
    {
        QueueMonitor::truncate();
        return redirect()->back()->with('success', 'Queue monitor cleared!');
    }
      public function api()
    {
        $jobs = QueueMonitor::orderBy('created_at', 'desc')->take(50)->get();
        $stats = $this->getQueueStats();
        
        return response()->json([
            'jobs' => $jobs,
            'stats' => $stats
        ]);
    }
     protected function getQueueStats()
    {
        return [
            'total' => QueueMonitor::count(),
            'pending' => QueueMonitor::where('status', 'pending')->count(),
            'processing' => QueueMonitor::where('status', 'processing')->count(),
            'completed' => QueueMonitor::where('status', 'completed')->count(),
            'failed' => QueueMonitor::where('status', 'failed')->count(),
        ];
    }

}
