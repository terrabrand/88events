<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'credits',
    ];

    /**
     * Get the attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (!$user->referral_code) {
                $user->referral_code = static::generateUniqueReferralCode();
            }
        });

        static::created(function ($user) {
            // Default: All attendees are promoters
            if ($user->hasRole('attendee')) {
                $user->assignRole('promoter');
            }
        });
    }

    public static function generateUniqueReferralCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }

    public function isPromoter(): bool
    {
        return $this->hasRole('promoter');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'credits' => 'decimal:2',
        ];
    }

    public function assignedEvents(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_scanner');
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest()
            ->first();
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function sentTickets()
    {
        return $this->hasMany(SupportTicket::class, 'sender_id');
    }

    public function receivedTickets()
    {
        return $this->hasMany(SupportTicket::class, 'recipient_id');
    }

    public function supportMessages()
    {
        return $this->hasMany(SupportMessage::class);
    }

    /* Promotion System */

    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }
    
    public function creditTransactions()
    {
        return $this->hasMany(CreditTransaction::class);
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    public function depositCredits(float $amount, string $description = 'Deposit')
    {
        $this->increment('credits', $amount);
        $this->creditTransactions()->create([
            'amount' => $amount,
            'type' => 'purchase',
            'description' => $description,
        ]);
    }

    public function chargeCredits(float $amount, string $description)
    {
        if ($this->credits < $amount) {
            throw new \Exception('Insufficient credits');
        }

        $this->decrement('credits', $amount);
        $this->creditTransactions()->create([
            'amount' => -$amount,
            'type' => 'spend',
            'description' => $description,
        ]);
    }

    /* Follow System */
    
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id')->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id')->withTimestamps();
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('followed_id', $user->id)->exists();
    }
}
