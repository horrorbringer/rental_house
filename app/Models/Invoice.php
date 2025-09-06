<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    /**
     * Invoice status constants
     */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_OVERDUE = 'overdue';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Available invoice statuses
     */
    public static $statuses = [
        self::STATUS_DRAFT,
        self::STATUS_PENDING,
        self::STATUS_PAID,
        self::STATUS_OVERDUE,
        self::STATUS_CANCELLED,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'invoice_number',
        'rental_id',
        'utility_usage_id',
        'billing_date',
        'due_date',
        'rent_amount',
        'total_water_fee',
        'total_electric_fee',
        'total_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'billing_date' => 'date',
        'due_date' => 'date',
        'rent_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($invoice) {
            if (!$invoice->invoice_number) {
                $invoice->invoice_number = 'INV-' . date('Ym') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });

        static::saving(function ($invoice) {
            $invoice->updateTotalAndBalance();
        });
    }

    /**
     * Get the rental that the invoice belongs to.
     */
    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function utilityUsage(): BelongsTo
    {
        return $this->belongsTo(UtilityUsage::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function updateTotalAndBalance()
    {
        $this->total_amount = $this->calculateTotal();
        $this->balance = $this->total_amount - $this->amount_paid;
        
        // Update status based on balance
        if ($this->balance <= 0) {
            $this->status = 'paid';
        } elseif ($this->due_date < now()) {
            $this->status = 'overdue';
        } else {
            $this->status = 'pending';
        }
    }

    protected function calculateTotal()
    {
        $total = $this->rent_amount + $this->other_charges;
        
        if ($this->utilityUsage) {
            $total += $this->utilityUsage->water_charge + $this->utilityUsage->electric_charge;
        }
        
        return $total;
    }

    public function addPayment($amount, $method, $reference = null, $proof = null, $notes = null)
    {
        $payment = $this->payments()->create([
            'payment_number' => 'PAY-' . date('Ym') . '-' . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT),
            'amount' => $amount,
            'payment_date' => now(),
            'payment_method' => $method,
            'reference_number' => $reference,
            'payment_proof' => $proof,
            'notes' => $notes,
        ]);

        $this->amount_paid += $amount;
        $this->save();

        return $payment;
    }
}
