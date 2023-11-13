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
        Schema::table('assets_requests', function (Blueprint $table) {
            $table->string('status')->default('Under Review');
            $table->text('additional_info')->nullable();
            $table->json('request_payload')->nullable();
            $table->string('commit_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets_requests', function (Blueprint $table) {
            $table->dropColumn(['status', 'additional_info', 'request_payload', 'commit_amount']);
        });
    }
};
