<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'division_id',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function materialInReceived()
    {
        return $this->hasMany(MaterialIn::class, 'received_by');
    }

    public function materialOutIssued()
    {
        return $this->hasMany(MaterialOut::class, 'issued_by');
    }

    public function productInReceived()
    {
        return $this->hasMany(ProductIn::class, 'received_by');
    }

    public function productionOrdersCreated()
    {
        return $this->hasMany(ProductionOrder::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Helpers - UPDATE INI
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isGudang()
    {
        return $this->role === 'gudang';
    }

    public function isProduksi()
    {
        return $this->role === 'produksi';
    }

    // TAMBAHKAN METHOD INI
    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function getRoleBadgeClass()
    {
        return match($this->role) {
            'admin' => 'badge-danger',
            'gudang' => 'badge-success',
            'produksi' => 'badge-warning',
            'owner' => 'badge-primary', // TAMBAHKAN BARIS INI
            default => 'badge-secondary'
        };
    }
}