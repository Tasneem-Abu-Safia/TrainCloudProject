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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trainee_id');
            $table->unsignedBigInteger('advisor_id');
            $table->dateTime('datetime');
            $table->enum('status', ['requested', 'accepted', 'completed', 'declined'])->default('requested');
            $table->text('details')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('trainee_id')->references('id')->on('trainees')->onDelete('cascade');
            $table->foreign('advisor_id')->references('id')->on('advisors')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
