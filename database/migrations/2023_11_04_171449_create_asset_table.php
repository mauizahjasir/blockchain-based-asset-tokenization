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
        Schema::create('assets', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('meta_id')->index();
            $table->string('name');
            $table->string('creator_wallet_address')->nullable();
            $table->unsignedBigInteger('quantity')->nullable();
            $table->foreignUuid('asset_type_id')->nullable();
            $table->string('tx_id')->nullable();
            $table->json('details')->nullable();
            $table->unsignedFloat('unit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
