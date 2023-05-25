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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('course_num');
            $table->text('desc');
            $table->foreignId('advisor_id')->constrained('advisors')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('field_id')->constrained('fields')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('duration');
            $table->enum('duration_unit', ['days', 'weeks', 'months'])->default('weeks');
            $table->string('location')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('fees')->nullable();
            $table->integer('capacity')->nullable();
            $table->integer('num_trainee')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
