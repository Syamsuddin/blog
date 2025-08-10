<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('spam_keywords', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->integer('weight')->default(10);
            $table->boolean('is_regex')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('category')->default('general');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['is_active', 'category']);
            $table->index('keyword');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spam_keywords');
    }
};
