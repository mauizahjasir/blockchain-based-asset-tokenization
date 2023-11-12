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
            $table->foreignUuid('asset_id')->constrained('assets');
            $table->foreignUuid('requestor_id')->constrained('users');
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
