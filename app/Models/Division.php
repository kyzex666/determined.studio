<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'division_code',
        'name',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($division) {
            if (empty($division->division_code)) {
                $lastDivision = self::withTrashed()->orderBy('id', 'desc')->first();
                $lastNumber = $lastDivision ? intval(substr($lastDivision->division_code, 3)) : 0;
                $division->division_code = 'DIV' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // Scopes
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
            ->orWhere('division_code', 'LIKE', "%{$search}%");
    }

    // Helpers
    public function getTotalUsers()
    {
        return $this->users()->count();
    }

    public function getActiveUsers()
    {
        return $this->users()->active()->count();
    }
}