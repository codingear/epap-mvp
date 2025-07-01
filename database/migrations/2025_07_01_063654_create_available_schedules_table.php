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
        Schema::create('available_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('timezone')->default('America/Mexico_City');
            $table->boolean('is_available')->default(true);
            $table->integer('max_slots')->default(1);
            $table->integer('booked_slots')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['date', 'is_available']);
            $table->index(['teacher_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('available_schedules');
    }
};
