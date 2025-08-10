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
        Schema::table('comments', function (Blueprint $table) {
            $table->integer('spam_score')->default(0)->after('is_approved');
            $table->json('spam_reasons')->nullable()->after('spam_score');
            $table->enum('status', ['pending', 'approved', 'rejected', 'spam'])->default('pending')->after('spam_reasons');
            $table->boolean('is_flagged')->default(false)->after('status');
            $table->string('ip_address', 45)->nullable()->after('is_flagged');
            $table->timestamp('reviewed_at')->nullable()->after('ip_address');
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('reviewed_at');
            
            $table->index(['status', 'is_approved']);
            $table->index('spam_score');
            $table->index('ip_address');
            
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropIndex(['status', 'is_approved']);
            $table->dropIndex(['spam_score']);
            $table->dropIndex(['ip_address']);
            $table->dropColumn([
                'spam_score', 'spam_reasons', 'status', 'is_flagged', 
                'ip_address', 'reviewed_at', 'reviewed_by'
            ]);
        });
    }
};
