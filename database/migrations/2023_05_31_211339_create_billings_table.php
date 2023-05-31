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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trainee_id')->unique();
            $table->decimal('amount_due', 8, 2);
            $table->enum('payment_status', ['active', 'inactive'])->default('inactive');
            $table->date('payment_date')->nullable();
            $table->string('visa')->unique();
            $table->string('cvc');
            $table->timestamps();
            $table->softDeletes();
            // Add foreign key constraint if necessary
            $table->foreign('trainee_id')->references('id')->on('trainees')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
