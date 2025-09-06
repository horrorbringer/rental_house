<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rental extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    /**
     * Rental status constants
     */
    public const STATUS_ACTIVE = 'active';
    public const STATUS_TERMINATED = 'terminated';
    public const STATUS_EXPIRED = 'expired';

    /**
     * Available rental statuses
     */
    public static $statuses = [
        self::STATUS_ACTIVE,
        self::STATUS_TERMINATED,
        self::STATUS_EXPIRED,
    ];

    protected $fillable = [
        'room_id',
        'tenant_id',
        'deposit',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'deposit' => 'decimal:2',
    ];

    /**
     * Get the room that the rental belongs to.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the tenant that the rental belongs to.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the utility usages for the rental.
     */
    public function utilityUsages(): HasMany
    {
        return $this->hasMany(UtilityUsage::class);
    }

    /**
     * Get the invoices for the rental.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}

