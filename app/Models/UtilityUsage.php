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
        'reading_date',
        'water_meter_start',
        'water_meter_end',
        'electric_meter_start',
        'electric_meter_end',
        'utility_rate_id',
        'water_meter_image_start',
        'water_meter_image_end',
        'electric_meter_image_start',
        'electric_meter_image_end',
        'notes',
    ];

    /**
     * Get the rental that the utility usage belongs to.
     */
    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function utilityRate(): BelongsTo
    {
        return $this->belongsTo(UtilityRate::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function getWaterUsageAttribute()
    {
        return $this->water_meter_end - $this->water_meter_start;
    }

    public function getElectricUsageAttribute()
    {
        return $this->electric_meter_end - $this->electric_meter_start;
    }

    public function getWaterChargeAttribute()
    {
        return $this->water_usage * $this->utilityRate->water_rate;
    }

    public function getElectricChargeAttribute()
    {
        return $this->electric_usage * $this->utilityRate->electric_rate;
    }
}
