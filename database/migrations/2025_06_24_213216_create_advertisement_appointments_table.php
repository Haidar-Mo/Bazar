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
        Schema::create('advertisement_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('advertisement_id')->constrained('advertisements')->cascadeOnDelete();
            $table->date('date');
            $table->time('time');
            $table->text('note')->nullable()->default('(لا يوجد)');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisement_appointments');
    }
};
