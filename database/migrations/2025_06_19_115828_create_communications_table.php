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
        Schema::create('communications', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('call_sid')->unique();
            $table->string('from');
            $table->string('from_formatted');
            $table->string('to');
            $table->string('to_formatted');
            $table->datetime('date_time');
            $table->string('duration');
            $table->string('recording_sid')->nullable();
            $table->string('recording_url_twilio')->nullable();
            $table->string('recording_url_axocall')->nullable();
            $table->string('recording_filename')->nullable();
            $table->longText('transcription_sid')->nullable();
            $table->longText('transcriptions')->nullable();
            $table->longText('summary')->nullable();
            $table->longText('notes')->nullable();
            $table->string('sentiment')->nullable();
            $table->string('keywords')->nullable();
            $table->string('is_booked')->nullable();
            $table->string('status')->nullable();
            $table->string('modified_by')->nullable();
            $table->timestamps(); 
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communications');
    }
};