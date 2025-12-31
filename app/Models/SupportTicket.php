<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'event_id',
        'subject',
        'status',
        'priority',
        'type',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function messages()
    {
        return $this->hasMany(SupportMessage::class);
    }
}
