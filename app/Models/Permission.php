<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RolePermission;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'permission_code',
        'name',
        'description',
        'module'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class, 'permission_code', 'permission_code');
    }

    // Scopes
    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }

    // Helpers
    public function getFormattedName()
    {
        return ucwords(str_replace('_', ' ', $this->permission_code));
    }
}