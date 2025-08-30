<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Room extends Model
{
    use HasFactory;

    /**
     * Room status constants
     */
    public const STATUS_VACANT = 'vacant';
    public const STATUS_OCCUPIED = 'occupied';

    /**
     * Available room statuses
     */
    public static $statuses = [
        self::STATUS_VACANT,
        self::STATUS_OCCUPIED,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'building_id',
        'room_number',
        'monthly_rent',
        'water_fee',
        'electric_fee',
        'status',
    ];

    /**
     * Get the building that the room belongs to.
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get the rental associated with the room.
     */
    public function rental(): HasOne
    {
        return $this->hasOne(Rental::class);
    }
}

