<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'permission_code'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_code', 'permission_code');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
