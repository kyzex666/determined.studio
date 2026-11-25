<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialIn extends Model
{
    use HasFactory;

    protected $table = 'material_in';

    protected $fillable = [
        'vendor_id',
        'material_id',
        'qty',
        'date',
        'note',
        'received_by'
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'date' => 'date',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
