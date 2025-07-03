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
        Schema::create('setting_extensions', function (Blueprint $table) {
            $table->id();
            $table->integer('extension_expiration_days')->default(30);
            $table->integer('extension_expiration_hrs')->default(0);
            $table->boolean('random_extension_generation')->default(false); 
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_extensions');
    }
};