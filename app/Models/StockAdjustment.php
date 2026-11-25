<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'product_id',
        'adjustment_type',
        'qty_before',
        'qty_after',
        'difference',
        'reason',
        'adjusted_by',
        'date'
    ];

    protected $casts = [
        'qty_before' => 'decimal:2',
        'qty_after' => 'decimal:2',
        'difference' => 'decimal:2',
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function adjustedBy()
    {
        return $this->belongsTo(User::class, 'adjusted_by');
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

    public function scopeByType($query, $type)
    {
        return $query->where('adjustment_type', $type);
    }

    // Helpers
    public function getAdjustmentTypeLabel()
    {
        return match($this->adjustment_type) {
            'opname' => 'Stock Opname',
            'correction' => 'Koreksi',
            'defect' => 'Barang Cacat',
            'sample' => 'Sample',
            default => 'Lainnya'
        };
    }

    public function getItemName()
    {
        if ($this->material_id) {
            return $this->material->name;
        } elseif ($this->product_id) {
            return $this->product->name;
        }
        return 'Unknown';
    }

    public function getItemUnit()
    {
        if ($this->material_id) {
            return $this->material->unit;
        } elseif ($this->product_id) {
            return $this->product->unit;
        }
        return 'pcs';
    }

    public function isIncrease()
    {
        return $this->difference > 0;
    }

    public function isDecrease()
    {
        return $this->difference < 0;
    }
}