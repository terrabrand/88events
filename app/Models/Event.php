<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'start_date',
        'end_date',
        'location_type',
        'venue_address',
        'streaming_url',
        'cover_image_path',
        'status',
        'organizer_id',
        'category_id',
        'tax_type',
        'tax_rate',
        'allow_promoters',
        'commission_type',
        'commission_rate',
        'venue_id',
        'has_seat_mapping',
        'is_featured',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'has_seat_mapping' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    public function scanners(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_scanner');
    }

    public function guests(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Guest::class, 'event_guest')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    public function activePromotion()
    {
        return $this->hasOne(Promotion::class)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->latest();
    }

    public function scopePromoted($query)
    {
        return $query->whereHas('promotions', function ($q) {
            $q->where('status', 'active')
              ->where('start_date', '<=', now())
              ->where('end_date', '>=', now());
        });
    }

    public function scopeWithPromotionStatus($query)
    {
        return $query->withExists(['promotions as is_promoted' => function ($q) {
            $q->where('status', 'active')
              ->where('start_date', '<=', now())
              ->where('end_date', '>=', now());
        }]);
    }
}
