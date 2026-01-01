<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'country',
        'lat',
        'lng',
        'google_place_id',
        'capacity',
        'seat_map_image',
        'is_global',
        'organizer_id',
        'seat_numbers',
    ];

    protected $casts = [
        'is_global' => 'boolean',
        'seat_numbers' => 'array',
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
