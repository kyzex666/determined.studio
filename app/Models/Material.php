<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'material_code',
        'name',
        'unit',
        'current_stock',
        'min_stock',
        'location',
        'category',
        'notes'
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'min_stock' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function materialIn()
    {
        return $this->hasMany(MaterialIn::class);
    }

    public function materialOut()
    {
        return $this->hasMany(MaterialOut::class);
    }

    public function productionMaterials()
    {
        return $this->hasMany(ProductionMaterial::class);
    }

    // NEW → Cek apakah material memiliki transaksi
    public function hasTransactions()
    {
        return $this->materialIn()->exists() || $this->materialOut()->exists();
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($material) {
            if (empty($material->material_code)) {
                $lastMaterial = self::withTrashed()->orderBy('id', 'desc')->first();
                $lastNumber = $lastMaterial ? intval(substr($lastMaterial->material_code, 3)) : 0;
                $material->material_code = 'MAT' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // Scopes
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
            ->orWhere('material_code', 'LIKE', "%{$search}%");
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= min_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Helpers
    public function isLowStock()
    {
        return $this->current_stock <= $this->min_stock;
    }

    public function isOutOfStock()
    {
        return $this->current_stock <= 0;
    }

    public function getStockStatus()
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        } elseif ($this->isLowStock()) {
            return 'low_stock';
        } else {
            return 'sufficient';
        }
    }

    public function getStockStatusColor()
    {
        return match($this->getStockStatus()) {
            'out_of_stock' => 'danger',
            'low_stock' => 'warning',
            'sufficient' => 'success',
            default => 'secondary'
        };
    }

    public function increaseStock($quantity)
    {
        $this->current_stock += $quantity;
        $this->save();
    }

    public function decreaseStock($quantity)
    {
        if ($this->current_stock >= $quantity) {
            $this->current_stock -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }
}
