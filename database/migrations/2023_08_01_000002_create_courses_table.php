<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->foreignId('level_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');
            $table->integer('order')->default(0);
            $table->string('slug')->unique();
            $table->string('cover_image')->nullable();
            $table->string('video_url')->nullable();
            $table->integer('duration')->nullable()->comment('Duration in minutes');
            $table->string('type')->default('lesson')->comment('lesson, quiz, assignment, etc.');
            $table->integer('completion_points')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
