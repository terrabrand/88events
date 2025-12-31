<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditTransaction extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'description',
        'reference_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
