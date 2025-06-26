<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    public function phoneNumbers()
    {
        return $this->hasMany(PhoneNumber::class, 'contact_id');
    }

    public function tags()
    {
        return $this->hasMany(ContactTag::class, 'contact_id')->with('tag');    
    }
}