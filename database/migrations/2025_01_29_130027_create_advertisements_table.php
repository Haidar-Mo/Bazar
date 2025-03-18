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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
            $table->string('title');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->enum('type', ['offer', 'order'])->default('offer');
            $table->string('currency_type');
            $table->boolean('negotiable')->default(0);
            $table->decimal('price', 16, 2);
            $table->boolean('is_special')->default(false);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->date('expiry_date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
