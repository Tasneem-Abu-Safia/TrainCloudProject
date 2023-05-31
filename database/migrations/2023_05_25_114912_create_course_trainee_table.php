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
        Schema::create('course_trainee', function (Blueprint $table) {
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('trainee_id')->constrained('trainees')->onDelete('cascade');
            $table->foreignId('advisor_id')->constrained('advisors')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->primary(['course_id', 'trainee_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_trainee');
    }
};
