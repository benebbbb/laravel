<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'medicine_name',
        'description',
        'category',
        'quantity',
        'expiration_date',
        'created_by',
    ];

    protected $casts = [
        'expiration_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isNearExpiration(): bool
    {
        return $this->expiration_date->diffInDays(now()) <= 7
            && $this->expiration_date->gte(now());
    }
}
