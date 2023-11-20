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
        Schema::create('assets_requests', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('meta_id')->index();
            $table->string('asset');
            $table->foreignUuid('requestor_id')->constrained('users');
            $table->string('status')->default('Under Review');
            $table->text('additional_info')->nullable();
            $table->unsignedBigInteger('commit_amount')->default(0);
            $table->json('request_payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets_requests');
    }
};
