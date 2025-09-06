<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Room extends Model
{
    use HasFactory;

    /**
     * The relationships that should be eager loaded.
     *
     * @var array
     */
    protected $with = ['building','latestRental'];

    /**
     * The cache tags for the model.
     *
     * @var array
     */
    protected static $cacheTags = ['rooms'];

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
        'image',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'monthly_rent' => 'decimal:2',
        'water_fee' => 'decimal:2',
        'electric_fee' => 'decimal:2'
    ];

    /**
     * Get the building that the room belongs to.
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get the current active rental for the room.
     */
    public function activeRental(): HasOne
    {
        return $this->hasOne(Rental::class)->whereNull('end_date');
    }

    /**
     * Get the latest rental for the room.
     */
    public function rental(): HasOne
    {
        return $this->hasOne(Rental::class)->latest();
    }

    /**
     * Get all rentals associated with the room.
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Get the images associated with the room.
     */
    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class);
    }

    /**
     * Active rentals (where end_date is null).
     * This is used when checking capacity and available slots.
     */
    public function activeRentals(): HasMany
    {
        return $this->hasMany(Rental::class)->whereNull('end_date');
    }

    /**
     * The latest rental (regardless of active or ended).
     */
    public function latestRental(): HasOne
    {
        return $this->hasOne(Rental::class)->latestOfMany();
    }

    /**
     * The latest active rental (only if still ongoing).
     */
    public function latestActiveRental(): HasOne
    {
        return $this->hasOne(Rental::class)
            ->whereNull('end_date')
            ->latestOfMany();
    }
}

