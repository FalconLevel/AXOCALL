<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactTag extends Model
{
    protected $guarded = ['id'];
    
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
}