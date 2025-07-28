
@extends('layouts.app')

@section('title', 'Job Details - QueueMaster')

@section('content')
<div class="space-y-6">
  
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('queue-monitor.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Job Details</h1>
        </div>
        
        <!-- Live Status Indicator -->
        <div class="flex items-center space-x-2" id="live-status">
            <div class="w-3 h-3 rounded-full animate-pulse" 
                 :class="{
                     'bg-green-500': status === 'completed',
                     'bg-blue-500': status === 'processing', 
                     'bg-yellow-500': status === 'pending',
                     'bg-red-500': status === 'failed'
                 }"
                 data-status="{{ $queueMonitor->status }}"></div>
            <span class="text-sm text-gray-600">Live Updates</span>
        </div>
    </div>

    <!-- Main Job Info Card -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
            <div class="flex items-center justify-between text-white">
                <div>
                    <h2 class="text-xl font-bold">{{ $queueMonitor->name }}</h2>
                    <p class="text-blue-100">Job ID: {{ $queueMonitor->job_id }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        @if($queueMonitor->status === 'completed') bg-green-100 text-green-800
                        @elseif($queueMonitor->status === 'processing') bg-blue-100 text-blue-800
                        @elseif($queueMonitor->status === 'failed') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif" id="status-badge">
                        <i class="fas 
                            @if($queueMonitor->status === 'completed') fa-check-circle
                            @elseif($queueMonitor->status === 'processing') fa-spinner fa-spin
                            @elseif($queueMonitor->status === 'failed') fa-exclamation-circle
                            @else fa-clock @endif mr-2"></i>
                        <span id="status-text">{{ ucfirst($queueMonitor->status) }}</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="px-6 py-4 bg-gray-50 border-b">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Progress</span>
                <span class="text-sm text-gray-500" id="progress-text">{{ $queueMonitor->progress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-gradient-to-r from-blue-500 to-green-500 h-3 rounded-full transition-all duration-500 ease-out" 
                     style="width: {{ $queueMonitor->progress }}%" id="progress-bar"></div>
            </div>
        </div>

        <!-- Job Details -->
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>Basic Information
                        </h3>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Queue:</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $queueMonitor->queue }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Attempts:</dt>
                                <dd class="text-sm text-gray-900" id="attempt-count">{{ $queueMonitor->attempt }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Database ID:</dt>
                                <dd class="text-sm text-gray-900">{{ $queueMonitor->id }}</dd>
                            </div>
                        </dl>
                    </div>

                 
                    @if($queueMonitor->data)
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-database text-blue-500 mr-2"></i>Job Data
                        </h3>
                        <div class="bg-white rounded border p-3">
                            <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($queueMonitor->data, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-clock text-green-500 mr-2"></i>Timeline
                        </h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created At:</dt>
                                <dd class="text-sm text-gray-900">
                                    <i class="fas fa-calendar-plus text-gray-400 mr-1"></i>
                                    {{ $queueMonitor->created_at->format('M d, Y H:i:s') }}
                                    <span class="text-xs text-gray-500">({{ $queueMonitor->created_at->diffForHumans() }})</span>
                                </dd>
                            </div>
                            
                            @if($queueMonitor->started_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Started At:</dt>
                                <dd class="text-sm text-gray-900" id="started-at">
                                    <i class="fas fa-play text-blue-400 mr-1"></i>
                                    {{ $queueMonitor->started_at->format('M d, Y H:i:s') }}
                                    <span class="text-xs text-gray-500">({{ $queueMonitor->started_at->diffForHumans() }})</span>
                                </dd>
                            </div>
                            @endif

                            @if($queueMonitor->finished_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Finished At:</dt>
                                <dd class="text-sm text-gray-900" id="finished-at">
                                    <i class="fas fa-check text-green-400 mr-1"></i>
                                    {{ $queueMonitor->finished_at->format('M d, Y H:i:s') }}
                                    <span class="text-xs text-gray-500">({{ $queueMonitor->finished_at->diffForHumans() }})</span>
                                </dd>
                            </div>
                            @endif

                            @if($queueMonitor->failed_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Failed At:</dt>
                                <dd class="text-sm text-gray-900" id="failed-at">
                                    <i class="fas fa-times text-red-400 mr-1"></i>
                                    {{ $queueMonitor->failed_at->format('M d, Y H:i:s') }}
                                    <span class="text-xs text-gray-500">({{ $queueMonitor->failed_at->diffForHumans() }})</span>
                                </dd>
                            </div>
                            @endif

                            @if($queueMonitor->duration)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Duration:</dt>
                                <dd class="text-sm text-gray-900" id="duration">
                                    <i class="fas fa-stopwatch text-purple-400 mr-1"></i>
                                    {{ $queueMonitor->duration }} seconds
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Performance Metrics -->
                    <div class="bg-green-50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-chart-line text-green-500 mr-2"></i>Performance
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600" id="progress-display">{{ $queueMonitor->progress }}%</div>
                                <div class="text-xs text-gray-500">Completed</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $queueMonitor->attempt }}</div>
                                <div class="text-xs text-gray-500">Attempts</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Information -->
    @if($queueMonitor->exception)
    <div class="bg-red-50 border-l-4 border-red-400 rounded-lg">
        <div class="p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-red-800">Error Information</h3>
                    <div class="mt-3 bg-red-100 rounded-lg p-4">
                        <pre class="text-sm text-red-700 whitespace-pre-wrap" id="exception-text">{{ $queueMonitor->exception }}</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex justify-between items-center bg-white rounded-lg shadow p-4">
        <div class="flex space-x-3">
            {{-- <button onclick="refreshJobDetails()" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded transition-colors">
                <i class="fas fa-sync-alt mr-2"></i>Refresh
            </button> --}}
            
            @if($queueMonitor->status === 'failed')
            <form method="POST" action="{{ route('queue-monitor.dispatch') }}" class="inline">
                @csrf
                <input type="hidden" name="data" value="{{ json_encode($queueMonitor->data) }}">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-700 text-white font-bold rounded transition-colors">
                    <i class="fas fa-redo mr-2"></i>Retry Job
                </button>
            </form>
            @endif
        </div>

        <div class="text-sm text-gray-500">
            Last updated: <span id="last-updated">{{ $queueMonitor->updated_at->format('H:i:s') }}</span>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
 
    function initializeEcho() {
        try {
            if (typeof window.Echo === 'undefined') {
                console.error('Echo is not initialized. Make sure:');
                console.error('1. app.js is properly loaded');
                console.error('2. Pusher credentials are correct');
                return false;
            }

            // Store job ID for real-time updates
            const jobId = {{ $queueMonitor->id }};

            // Setup channel and listeners
            const channel = window.Echo.channel('queue-monitor');
            
            channel.listen('.job.updated', (e) => {
                console.log('Job update received:', e);
                if (e.job && e.job.id === jobId) {
                    updateJobDetails(e.job);
                }
            });
            
            channel.error((error) => {
                console.error('Channel error:', error);
            });

            // Connection state listeners
            window.Echo.connector.pusher.connection.bind('connected', () => {
                console.log('Pusher connected successfully');
            });

            window.Echo.connector.pusher.connection.bind('error', (error) => {
                console.error('Pusher connection error:', error);
            });

            return true;
        } catch (error) {
            console.error('Error initializing Echo:', error);
            return false;
        }
    }

    
    const echoCheckInterval = setInterval(() => {
        if (typeof window.Echo !== 'undefined') {
            clearInterval(echoCheckInterval);
            if (!initializeEcho()) {
                showNotification('Failed to initialize real-time updates', 'error');
            }
        }
    }, 100);

    // Update job details function
    function updateJobDetails(job) {
        try {
           
            const statusBadge = document.getElementById('status-badge');
            const statusText = document.getElementById('status-text');
            statusBadge.className = `inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${getStatusClasses(job.status)}`;
            statusText.textContent = job.status.charAt(0).toUpperCase() + job.status.slice(1);

           
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            const progressDisplay = document.getElementById('progress-display');
            
            progressBar.style.width = `${job.progress}%`;
            progressText.textContent = `${job.progress}%`;
            progressDisplay.textContent = `${job.progress}%`;

          
            document.getElementById('attempt-count').textContent = job.attempt;

          
            if (job.started_at && !document.getElementById('started-at')) {
                location.reload();
            }
            
            if (job.finished_at && !document.getElementById('finished-at')) {
                location.reload();
            }

            if (job.failed_at && !document.getElementById('failed-at')) {
                location.reload();
            }

            // Update last updated time
            document.getElementById('last-updated').textContent = new Date().toLocaleTimeString();

            // Update live status indicator
            const liveStatus = document.querySelector('[data-status]');
            if (liveStatus) {
                liveStatus.setAttribute('data-status', job.status);
                liveStatus.className = `w-3 h-3 rounded-full animate-pulse ${getStatusColor(job.status)}`;
            }

            showNotification('Job updated in real-time!', 'success');
        } catch (error) {
            console.error('Error updating job details:', error);
        }
    }

    // Helper functions
    function getStatusClasses(status) {
        const classes = {
            'completed': 'bg-green-100 text-green-800',
            'processing': 'bg-blue-100 text-blue-800',
            'failed': 'bg-red-100 text-red-800',
            'pending': 'bg-yellow-100 text-yellow-800'
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    }

    function getStatusColor(status) {
        const colors = {
            'completed': 'bg-green-500',
            'processing': 'bg-blue-500',
            'failed': 'bg-red-500',
            'pending': 'bg-yellow-500'
        };
        return colors[status] || 'bg-gray-500';
    }

    // function refreshJobDetails() {
    //     location.reload();
    // }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 'bg-blue-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Initialize live status indicator
    const status = document.querySelector('[data-status]')?.getAttribute('data-status');
    const liveStatus = document.querySelector('[data-status]');
    if (liveStatus && status) {
        liveStatus.className = `w-3 h-3 rounded-full animate-pulse ${getStatusColor(status)}`;
    }
});
</script>
@endsection