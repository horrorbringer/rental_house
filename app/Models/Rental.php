<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rental extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'room_id',
        'tenant_id',
        'start_date',
        'end_date',
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
     * Get the utility usage record associated with the rental.
     */
    public function utilityUsage(): HasOne
    {
        return $this->hasOne(UtilityUsage::class);
    }

    /**
     * Get the invoices for the rental.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}

