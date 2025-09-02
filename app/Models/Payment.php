<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'payment_number',
        'invoice_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'payment_proof',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    /**
     * Get the invoice that the payment belongs to.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
