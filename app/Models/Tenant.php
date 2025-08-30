<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'id_card_front_path',
        'id_card_back_path',
    ];

    /**
     * Get the rentals for the tenant.
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }
}
