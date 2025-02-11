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
        Schema::create('favorite_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('favorite_list_id')->constrained('favorite_lists')->cascadeOnDelete();
            $table->foreignId('advertisement_id')->constrained('advertisements')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_list_items');
    }
};
