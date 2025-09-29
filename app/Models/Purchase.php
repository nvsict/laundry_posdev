<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'purchase_date',
        'reference_no',
        'total_amount',
        'status',
    ];

    /**
     * A purchase belongs to one supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * A purchase can have many products.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_purchase')
                    ->withPivot('quantity', 'unit_price') // Include extra pivot fields
                    ->withTimestamps();
    }
}