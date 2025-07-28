@extends('layouts.app')

@section('title', 'Queue Monitor Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4" id="stats-container">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-list text-gray-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Jobs</p>
                        <p class="text-2xl font-semibold text-gray-900" id="total-count">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending</p>
                        <p class="text-2xl font-semibold text-yellow-600" id="pending-count">{{ $stats['pending'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-spinner text-blue-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Processing</p>
                        <p class="text-2xl font-semibold text-blue-600" id="processing-count">{{ $stats['processing'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Completed</p>
                        <p class="text-2xl font-semibold text-green-600" id="completed-count">{{ $stats['completed'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Failed</p>
                        <p class="text-2xl font-semibold text-red-600" id="failed-count">{{ $stats['failed'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Queue Jobs</h2>
            <div class="space-x-2">
                <form method="POST" action="{{ route('queue-monitor.dispatch') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-plus mr-2"></i>Dispatch Job
                    </button>
                </form>
                <form method="POST" action="{{ route('queue-monitor.clear') }}" class="inline"
                    onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-trash mr-2"></i>Clear All
                    </button>
                </form>
            </div>
        </div>

        <!-- Jobs Table -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Queue
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="jobs-table">
                    @foreach ($jobs as $job)
                        <tr data-job-id="{{ $job->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $job->name }}</div>
                                <div class="text-sm text-gray-500">ID: {{ $job->job_id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $job->queue }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if ($job->status === 'completed') bg-green-100 text-green-800
                            @elseif($job->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($job->status === 'failed') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $job->progress }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500">{{ $job->progress }}%</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $job->duration ? $job->duration . 's' : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $job->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('queue-monitor.show', $job) }}"
                                    class="text-indigo-600 hover:text-indigo-900">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $jobs->links() }}
        </div>
    </div>

    {{-- <script>
window.Echo.channel('queue-monitor')
    .listen('.job.updated', (e) => {
        updateJobRow(e.job);
        updateStats();
    });

function updateJobRow(job) {
    const row = document.querySelector(`tr[data-job-id="${job.id}"]`);
    if (row) {
       
        updateRowContent(row, job);
    } else {
       
        addNewJobRow(job);
    }
}

function updateRowContent(row, job) {
    const statusBadge = row.querySelector('span');
    const progressBar = row.querySelector('.bg-blue-600');
    const progressText = row.querySelector('.text-xs.text-gray-500');
    
    // Update status
    statusBadge.className = `px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClasses(job.status)}`;
    statusBadge.textContent = job.status.charAt(0).toUpperCase() + job.status.slice(1);
    
    // Update progress
    progressBar.style.width = `${job.progress}%`;
    progressText.textContent = `${job.progress}%`;
}

function getStatusClasses(status) {
    const classes = {
        'completed': 'bg-green-100 text-green-800',
        'processing': 'bg-blue-100 text-blue-800',
        'failed': 'bg-red-100 text-red-800',
        'pending': 'bg-yellow-100 text-yellow-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

function addNewJobRow(job) {
    // Implementation for adding new rows dynamically
    location.reload(); // Simple refresh for now
}

function updateStats() {
    fetch('/queue-monitor/api')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-count').textContent = data.stats.total;
            document.getElementById('pending-count').textContent = data.stats.pending;
            document.getElementById('processing-count').textContent = data.stats.processing;
            document.getElementById('completed-count').textContent = data.stats.completed;
            document.getElementById('failed-count').textContent = data.stats.failed;
        });
}
</script> --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
   
    const initEcho = setInterval(() => {
        if (window.Echo) {
            clearInterval(initEcho);
            setupEchoListeners();
        }
    }, 100);

    function setupEchoListeners() {
        console.log('Initializing Echo listeners...');
        
        window.Echo.channel('queue-monitor')
            .listen('.job.updated', (e) => {
                console.log('Job update received:', e);
                updateJobRow(e.job);
                updateStats();
            })
            .error((error) => {
                console.error('Echo channel error:', error);
            });

      
        window.Echo.connector.pusher.connection.bind('connected', () => {
            console.log('Pusher connected successfully');
        });

        window.Echo.connector.pusher.connection.bind('error', (error) => {
            console.error('Pusher connection error:', error);
        });
    }

    function updateJobRow(job) {
       
        const row = document.querySelector(`tr[data-job-id="${job.id}"]`);
        
        if (row) {
            updateRowContent(row, job);
        } else {
            addNewJobRow(job);
        }
    }

    function updateRowContent(row, job) {
        try {
            const statusBadge = row.querySelector('span');
            const progressBar = row.querySelector('.bg-blue-600');
            const progressText = row.querySelector('.text-xs.text-gray-500');
            
          
            statusBadge.className = `px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClasses(job.status)}`;
            statusBadge.textContent = job.status.charAt(0).toUpperCase() + job.status.slice(1);
            
            // Update progress
            progressBar.style.width = `${job.progress}%`;
            progressText.textContent = `${job.progress}%`;
        } catch (error) {
            console.error('Error updating row:', error);
        }
    }

    function getStatusClasses(status) {
        const classes = {
            'completed': 'bg-green-100 text-green-800',
            'processing': 'bg-blue-100 text-blue-800',
            'failed': 'bg-red-100 text-red-800',
            'pending': 'bg-yellow-100 text-yellow-800'
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    }

    function addNewJobRow(job) {
        console.log('Adding new job:', job.id);
        location.reload(); // Consider implementing dynamic row addition
    }

    function updateStats() {
        fetch('/queue-monitor/api')
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                document.getElementById('total-count').textContent = data.stats.total;
                document.getElementById('pending-count').textContent = data.stats.pending;
                document.getElementById('processing-count').textContent = data.stats.processing;
                document.getElementById('completed-count').textContent = data.stats.completed;
                document.getElementById('failed-count').textContent = data.stats.failed;
            })
            .catch(error => {
                console.error('Error updating stats:', error);
            });
    }
});
</script>

  
@endsection
