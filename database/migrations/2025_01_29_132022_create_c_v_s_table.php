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
        Schema::create('c_v_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('cv_path')->nullable();
            $table->string('full_name');
            $table->text('summary')->nullable();
            $table->string('image')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->enum('gender', ['male', 'female']);
            $table->json('language')->nullable();
            $table->string('nationality');
            $table->date('birth_date')->nullable();
            $table->text('skills')->nullable();
            $table->text('education')->nullable();
            $table->text('experience')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_v_s');
    }
};
