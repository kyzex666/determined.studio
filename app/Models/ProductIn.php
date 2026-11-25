<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductIn extends Model
{
    use HasFactory;

    protected $table = 'product_in';

    protected $fillable = [
        'transaction_code',
        'production_order_id',
        'product_id',
        'qty',
        'date',
        'condition',
        'defect_notes',
        'received_by'
    ];

    protected $casts = [
        'qty' => 'integer',
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($productIn) {
            if (empty($productIn->transaction_code)) {
                $year = date('Y', strtotime($productIn->date ?? now()));
                $lastTransaction = self::whereYear('date', $year)->count();
                $productIn->transaction_code = 'PI/' . $year . '/' . str_pad($lastTransaction + 1, 4, '0', STR_PAD_LEFT);
            }
        });

        static::created(function ($productIn) {
            // Update product stock if condition is good
            if ($productIn->condition === 'good') {
                $productIn->product->increaseStock($productIn->qty);
            }
        });

        static::updated(function ($productIn) {
            // Handle stock adjustment if qty or condition changed
            $originalQty = $productIn->getOriginal('qty');
            $originalCondition = $productIn->getOriginal('condition');
            $newQty = $productIn->qty;
            $newCondition = $productIn->condition;
            
            // If condition changed from good to defect
            if ($originalCondition === 'good' && $newCondition === 'defect') {
                $productIn->product->decreaseStock($originalQty);
            }
            // If condition changed from defect to good
            elseif ($originalCondition === 'defect' && $newCondition === 'good') {
                $productIn->product->increaseStock($newQty);
            }
            // If condition remains good but qty changed
            elseif ($originalCondition === 'good' && $newCondition === 'good' && $originalQty != $newQty) {
                $difference = $newQty - $originalQty;
                if ($difference > 0) {
                    $productIn->product->increaseStock($difference);
                } else {
                    $productIn->product->decreaseStock(abs($difference));
                }
            }
        });

        static::deleted(function ($productIn) {
            // Decrease product stock if condition was good
            if ($productIn->condition === 'good') {
                $productIn->product->decreaseStock($productIn->qty);
            }
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

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByProductionOrder($query, $productionOrderId)
    {
        return $query->where('production_order_id', $productionOrderId);
    }

    public function scopeGoodCondition($query)
    {
        return $query->where('condition', 'good');
    }

    public function scopeDefectCondition($query)
    {
        return $query->where('condition', 'defect');
    }

    // Helpers
    public function getFormattedDate()
    {
        return $this->date->format('d/m/Y');
    }

    public function getConditionColor()
    {
        return match($this->condition) {
            'good' => 'success',
            'defect' => 'danger',
            default => 'secondary'
        };
    }

    public function getConditionLabel()
    {
        return match($this->condition) {
            'good' => 'Baik',
            'defect' => 'Cacat',
            default => 'Tidak Diketahui'
        };
    }
}