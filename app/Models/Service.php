<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // ✅ Correct import

class Service extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'price',
        'price_type',
        'service_type_id',
        'barcode',
    ];

    /**
     * Defines the relationship to get the category for a service.
     */
    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    /**
     * Defines the relationship to orders.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_service');
    }

    /**
     * Auto-generate barcode when creating a new service.
     */
    protected static function booted()
    {
        static::creating(function ($service) {
            if (empty($service->barcode)) {
                $lastId = self::max('id') + 1; // unique incremental number

                // Fetch prefix and suffix from settings
                $prefix = \App\Models\Setting::get('barcode_prefix', '');
                $suffix = \App\Models\Setting::get('barcode_suffix', '');

                // Generate barcode
                $service->barcode = $prefix . str_pad($lastId, 6, '0', STR_PAD_LEFT) . $suffix;
            }
        });
    }
}
