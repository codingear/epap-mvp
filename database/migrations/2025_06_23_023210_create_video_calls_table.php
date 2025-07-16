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
        Schema::create('video_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('day');
            $table->string('time'); // Changed from time to string
            $table->string('timezone');
            $table->string('url');
            $table->enum('status', ['scheduled', 'canceled', 'ready'])->default('scheduled');
            
            $table->string('google_meet_id')->nullable();
            $table->text('google_meet_link')->nullable();
            $table->json('google_meet_data')->nullable();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('scheduled_at')->nullable();
            $table->datetime('started_at')->nullable();
            $table->datetime('ended_at')->nullable();
            $table->text('meeting_notes')->nullable();
            $table->enum('type', ['welcome', 'lesson', 'support'])->default('welcome');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_calls');
    }
};
