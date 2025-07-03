<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Communication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'call_sid',
        'from',
        'from_formatted',
        'to',
        'to_formatted',
        'date_time',
        'duration',
        'recording_sid',
        'recording_url_twilio',
        'recording_url_axocall',
        'recording_filename',
        'transcription_sid',
        'transcriptions',
        'summary',
        'notes',
        'sentiment',
        'keywords',
        'is_booked',
        'category',
        'status',
        'modified_by'
    ];

    protected $casts = [
        'date_time' => 'datetime',
        'is_booked' => 'boolean',
    ];

    /**
     * Get the sentiment analysis data
     */
    public function getSentimentAnalysisAttribute()
    {
        return [
            'sentiment' => $this->sentiment,
            'keywords' => $this->keywords,
            'summary' => $this->summary,
            'is_booked' => $this->is_booked,
        ];
    }

    /**
     * Scope for positive sentiment calls
     */
    public function scopePositive($query)
    {
        return $query->where('sentiment', 'positive');
    }

    /**
     * Scope for negative sentiment calls
     */
    public function scopeNegative($query)
    {
        return $query->where('sentiment', 'negative');
    }

    /**
     * Scope for booked calls
     */
    public function scopeBooked($query)
    {
        return $query->where('is_booked', 'yes');
    }

    /**
     * Scope for inbound calls
     */
    public function scopeInbound($query)
    {
        return $query->where('type', 'inbound');
    }

    /**
     * Scope for outbound calls
     */
    public function scopeOutbound($query)
    {
        return $query->where('type', 'outbound');
    }

    public function transcriptions()
    {
        return $this->hasMany(Transcription::class, 'recording_id', 'recording_sid');
    }

    public function contact_from()
    {
        return $this->belongsTo(PhoneNumber::class, 'from', 'phone_number_formatted')->with('contact');
    }

    public function contact_to()
    {
        return $this->belongsTo(PhoneNumber::class, 'to', 'phone_number_formatted')->with('contact');
    }
}