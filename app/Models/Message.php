<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = ['id'];

    public function contact_from()
    {
        return $this->belongsTo(PhoneNumber::class, 'from_number', 'phone_number_formatted')->with('contact');
    }

    public function contact_to()
    {
        return $this->belongsTo(PhoneNumber::class, 'to_number', 'phone_number_formatted')->with('contact');
    }
    //
}