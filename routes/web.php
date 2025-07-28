<?php

use App\Http\Controllers\QueueMonitorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return redirect()->route('queue-monitor.index');
});

Route::prefix('queue-monitor')->name('queue-monitor.')->group(function () {
    Route::get('/', [QueueMonitorController::class, 'index'])->name('index');
    Route::get('/api', [QueueMonitorController::class, 'api'])->name('api');
    Route::get('/{queueMonitor}', [QueueMonitorController::class, 'show'])->name('show');
    Route::post('/dispatch', [QueueMonitorController::class, 'dispatch'])->name('dispatch');
    Route::delete('/clear', [QueueMonitorController::class, 'clear'])->name('clear');
});
