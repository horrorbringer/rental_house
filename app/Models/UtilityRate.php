<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UtilityRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'effective_date',
        'water_rate',
        'electric_rate',
        'notes',
    ];

    protected $casts = [
        'effective_date' => 'date',
    ];

    public function utilityUsages(): HasMany
    {
        return $this->hasMany(UtilityUsage::class);
    }

    public static function getCurrentRate()
    {
        return static::where('effective_date', '<=', now())
            ->orderBy('effective_date', 'desc')
            ->first();
    }
}
