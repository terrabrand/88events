<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'name',
        'email',
        'phone',
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_guest')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
