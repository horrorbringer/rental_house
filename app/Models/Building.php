<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Building extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'contact_info',
        'description',
    ];

    public function primaryImage()
    {
        return $this->hasOne(BuildingImage::class)->where('is_primary', true);
    }

    public function images()
    {
        return $this->hasMany(BuildingImage::class);
    }

    /**
     * Get the user that owns the building.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the rooms for the building.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
