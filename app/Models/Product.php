<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Field yang boleh diisi oleh user (Mass Assignment).
     * Harus sesuai dengan name="" di Form Input.
     */
    protected $fillable = [
        'name',
        'sku',
        'current_stock',
        'unit',
        'notes',
    ];

    /**
     * Konversi tipe data otomatis.
     */
    protected $casts = [
        'current_stock' => 'integer',
        'min_stock'     => 'integer',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime'
    ];

    /* =====================
     | RELATIONSHIPS
     ===================== */

    // Relasi ke Production Order (1 Produk bisa punya banyak PO)
    public function productionOrders()
    {
        return $this->hasMany(ProductionOrder::class);
    }

    // Relasi ke Resep/BOM (1 Produk punya banyak bahan baku)
    public function materials()
    {
        return $this->hasMany(ProductMaterial::class);
    }

    // Relasi ke Product In (Riwayat barang masuk)
    public function productIn()
    {
        return $this->hasMany(ProductIn::class);
    }

    /* =====================
     | STOCK MANAGEMENT
     ===================== */

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