<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transcription extends Model {
    protected $guarded = ['id'];

    public function recording() {
        return $this->belongsTo(Communication::class);
    }
}