<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'price',
        'quantity',
        'amount',
        'product_id',
    ];

    /**
     * Public searchable property
     */
    public $searchable = [
        'code', 'price', 'quantity', 'amount', 'product_id'
    ];

    /**
     * Public selectable property
     */
    public $selectable = [
        'transactions.id', 'transactions.code', 'transactions.price', 'transactions.quantity', 'transactions.amount', 'transactions.product_id',
    ];

    /**
     * Get the product that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
