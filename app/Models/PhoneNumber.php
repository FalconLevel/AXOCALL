<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{

    protected $guarded = ['id'];

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }       
}