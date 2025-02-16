<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'permissions'
    ];

    protected $casts = [
        'permissions' => 'array'
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Helper methods
    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []);
    }

    public function hasAnyPermission(array $permissions)
    {
        return !empty(array_intersect($permissions, $this->permissions ?? []));
    }

    public function hasAllPermissions(array $permissions)
    {
        return empty(array_diff($permissions, $this->permissions ?? []));
    }
}
