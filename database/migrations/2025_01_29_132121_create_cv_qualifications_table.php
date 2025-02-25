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
        Schema::create('cv_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cvs')->cascadeOnDelete();
            $table->string('certificate'); // Example: Bachelor's, Master's, PhD
            $table->string('specialization'); // Example: Computer Science, Business
            $table->string('university');
            $table->string('country');
            $table->date('entering_date');
            $table->date('graduation_date')->nullable(); // Nullable if still studying
            $table->boolean('still_studying')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_qualifications');
    }
};
