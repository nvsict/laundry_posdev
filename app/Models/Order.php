<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_number',
        'subtotal', // <-- Add
        'service_charge_amount', // <-- Add
        'tax_rate', // <-- Add
        'tax_amount', // <-- Add
        'grand_total', // <-- Updated from total_amount
        'status',
        'payment_status',
        'order_date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'order_service')
                    ->withPivot('quantity', 'unit_price')
                    ->withTimestamps();
    }
}
