<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'price', 'stock'
    ];

    /**
     * Public searchable property
     */
    public $searchable = [
        'name', 'description', 'price', 'stock'
    ];

    /**
     * Public selectable property
     */
    public $selectable = [
        'products.id', 'products.name', 'products.price', 'products.stock',
    ];
}
