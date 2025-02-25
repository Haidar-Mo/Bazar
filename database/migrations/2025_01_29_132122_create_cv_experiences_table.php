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
        Schema::create('cv_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')->constrained('cvs')->cascadeOnDelete();
            $table->string('job_name');
            $table->string('job_type'); // Example: Full-time, Part-time, etc.
            $table->string('company_sector'); // Example: IT, Finance, Education
            $table->string('company_name');
            $table->string('country');
            $table->text('job_description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Nullable if it's a current job
            $table->boolean('current_job')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_experiences');
    }
};
