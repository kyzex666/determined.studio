<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionMaterial extends Model
{
    protected $fillable = [
        'production_order_id',
        'material_id',
        'required_qty',
        'issued_qty',
        'unit',
        'notes',
    ];

    protected $casts = [
        'required_qty' => 'decimal:2',
        'issued_qty' => 'decimal:2',
    ];

    /* =====================
     | RELATIONS
     =====================*/
    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    /* =====================
     | HELPERS
     =====================*/
    public function getRemainingQtyAttribute()
    {
        return $this->required_qty - $this->issued_qty;
    }

    public function isFullyIssued()
    {
        return $this->issued_qty >= $this->required_qty;
    }

    public function progressPercentage()
    {
        if ($this->required_qty == 0) return 100;
        return min(100, ($this->issued_qty / $this->required_qty) * 100);
    }
}