<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('queue_monitor', function (Blueprint $table) {
            $table->id();
             $table->string('job_id')->unique();
            $table->string('name');
            $table->string('queue');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->integer('attempt')->default(0);
            $table->integer('progress')->default(0);
            $table->json('data')->nullable();
            $table->text('exception')->nullable();
            $table->string('status')->default('pending'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_monitor');
    }
};
