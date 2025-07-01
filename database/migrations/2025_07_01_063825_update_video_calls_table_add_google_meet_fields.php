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
        Schema::table('video_calls', function (Blueprint $table) {
            $table->string('google_meet_id')->nullable()->after('url');
            $table->text('google_meet_link')->nullable()->after('google_meet_id');
            $table->json('google_meet_data')->nullable()->after('google_meet_link');
            $table->foreignId('teacher_id')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            $table->datetime('scheduled_at')->nullable()->after('timezone');
            $table->datetime('started_at')->nullable()->after('scheduled_at');
            $table->datetime('ended_at')->nullable()->after('started_at');
            $table->text('meeting_notes')->nullable()->after('ended_at');
            $table->enum('type', ['welcome', 'lesson', 'support'])->default('welcome')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('video_calls', function (Blueprint $table) {
            $table->dropColumn([
                'google_meet_id',
                'google_meet_link', 
                'google_meet_data',
                'teacher_id',
                'scheduled_at',
                'started_at',
                'ended_at',
                'meeting_notes',
                'type'
            ]);
        });
    }
};
