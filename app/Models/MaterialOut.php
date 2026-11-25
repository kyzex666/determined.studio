<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialOut extends Model
{
    use HasFactory;

    protected $table = 'material_out';

    protected $fillable = [
        'transaction_code',
        'production_order_id',
        'material_id',
        'qty',
        'date',
        'notes',
        'issued_by'
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($materialOut) {
            if (empty($materialOut->transaction_code)) {
                $year = date('Y', strtotime($materialOut->date ?? now()));
                $lastTransaction = self::whereYear('date', $year)->count();
                $materialOut->transaction_code = 'MO/' . $year . '/' . str_pad($lastTransaction + 1, 4, '0', STR_PAD_LEFT);
            }
        });

        static::created(function ($materialOut) {
            // Decrease material stock
            $materialOut->material->decreaseStock($materialOut->qty);
        });

        static::updated(function ($materialOut) {
            // Recalculate material stock if qty changed
            $originalQty = $materialOut->getOriginal('qty');
            $newQty = $materialOut->qty;
            
            if ($originalQty != $newQty) {
                $difference = $newQty - $originalQty;
                if ($difference > 0) {
                    // Additional stock taken
                    $materialOut->material->decreaseStock($difference);
                } else {
                    // Stock returned
                    $materialOut->material->increaseStock(abs($difference));
                }
            }
        });

        static::deleted(function ($materialOut) {
            // Return material stock when deleted
            $materialOut->material->increaseStock($materialOut->qty);
        });
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', date('m'))
                     ->whereYear('date', date('Y'));
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeByProductionOrder($query, $productionOrderId)
    {
        return $query->where('production_order_id', $productionOrderId);
    }

    public function scopeByMaterial($query, $materialId)
    {
        return $query->where('material_id', $materialId);
    }

    // Helpers
    public function getFormattedDate()
    {
        return $this->date->format('d/m/Y');
    }

    public function getFormattedQty()
    {
        return number_format($this->qty, 2) . ' ' . $this->material->unit;
    }
}