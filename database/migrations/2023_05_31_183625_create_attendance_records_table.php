<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('trainee_id');
            $table->enum('status', ['present', 'absent']);
            $table->date('date');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('trainee_id')->references('id')->on('trainees')->onDelete('cascade');
            $table->primary(['course_id', 'trainee_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
