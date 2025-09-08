<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UtilityUsage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'rental_id',
        'water_usage',
        'electric_usage',
        'reading_date',
        'notes',
    ];

    protected $casts = [
        'reading_date' => 'date',
        'water_usage' => 'decimal:2',
        'electric_usage' => 'decimal:2',
        'is_initial_reading' => 'boolean',
    ];

    /**
     * Get the rental that the utility usage belongs to.
     */
    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Get the total water charge.
     */
    public function getWaterChargeAttribute()
    {
        return $this->water_usage * ($this->rental->room->water_fee ?? 0);
    }

    /**
     * Get the total electric charge.
     */
    public function getElectricChargeAttribute()
    {
        return $this->electric_usage * ($this->rental->room->electric_fee ?? 0);
    }

    /**
     * Get the total utility charge.
     */
    public function getTotalChargeAttribute()
    {
        return $this->water_charge + $this->electric_charge;
    }
}
