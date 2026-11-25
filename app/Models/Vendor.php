<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_code',
        'name',
        'address',
        'phone',
        'email',
        'notes'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function materialIn()
    {
        return $this->hasMany(MaterialIn::class);
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vendor) {
            if (empty($vendor->vendor_code)) {
                $lastVendor = self::withTrashed()->orderBy('id', 'desc')->first();
                $lastNumber = $lastVendor ? intval(substr($lastVendor->vendor_code, 3)) : 0;
                $vendor->vendor_code = 'VEN' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // Scopes
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
            ->orWhere('vendor_code', 'LIKE', "%{$search}%")
            ->orWhere('phone', 'LIKE', "%{$search}%");
    }

    // Helpers
    public function getTotalTransactions()
    {
        return $this->materialIn()->count();
    }

    public function getTotalSpent()
    {
        return $this->materialIn()->sum('total_price');
    }
}