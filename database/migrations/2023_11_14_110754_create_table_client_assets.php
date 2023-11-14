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
        Schema::create('client_assets', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('meta_id')->index();
            $table->foreignUuid('asset_id')->constrained('assets');
            $table->string('owner_id');
            $table->string('previous_owner_id')->nullable();
            $table->foreignUuid('tx_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_client_assets');
    }
};
