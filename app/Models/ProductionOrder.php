<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ProductIn;

class ProductionOrder extends Model
{
    protected $fillable = [
        'product_id',
        'order_code',
        'target_qty',
        'actual_qty',
        'start_date',
        'end_date',
        'status',
        'priority',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* =====================
     | RELATIONS
     =====================*/
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(ProductionMaterial::class);
    }

    // Tambahkan relasi ke user pembuat
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke MaterialOut
    public function materialOuts(): HasMany
    {
        return $this->hasMany(MaterialOut::class);
    }

    // Relasi ke ProductIn (hasil produksi yang masuk gudang)
    public function productIn(): HasMany
    {
        return $this->hasMany(ProductIn::class);
    }

    /* =====================
     | SCOPES
     =====================*/
    public function scopeWaitingMaterial($query)
    {
        return $query->where('status', 'waiting_material');
    }

    public function scopeInProcess($query)
    {
        return $query->where('status', 'process');
    }

    /* =====================
     | HELPERS
     =====================*/
    public function isCompleted(): bool
    {
        return $this->status === 'done';
    }

    public function isWaitingMaterial(): bool
    {
        return $this->status === 'waiting_material';
    }

    public function isProcess(): bool
    {
        return $this->status === 'process';
    }

    // Hitung progress material
    public function materialProgress(): array
    {
        $materials = $this->materials;
        if ($materials->isEmpty()) {
            return ['percentage' => 0, 'message' => 'No materials required'];
        }

        $totalRequired = $materials->sum('required_qty');
        $totalIssued = $materials->sum('issued_qty');
        
        $percentage = $totalRequired > 0 ? ($totalIssued / $totalRequired) * 100 : 0;

        return [
            'percentage' => round($percentage, 2),
            'issued' => $totalIssued,
            'required' => $totalRequired,
            'remaining' => $totalRequired - $totalIssued,
        ];
    }

    // Cek apakah material sudah siap
    public function isMaterialReady(): bool
    {
        $progress = $this->materialProgress();
        return $progress['percentage'] >= 100;
    }
}