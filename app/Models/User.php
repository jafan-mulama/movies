<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'subscription_plan_id',
        'subscription_ends_at',
        'avatar',
        'bio',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'subscription_ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Helper methods
    public function hasRole($roleName)
    {
        return $this->role->slug === $roleName;
    }

    public function hasPermission($permission)
    {
        return $this->role->hasPermission($permission);
    }

    public function hasActiveSubscription()
    {
        if (!$this->subscription_plan_id) {
            return false;
        }

        return $this->subscription_ends_at === null || 
               $this->subscription_ends_at->isFuture();
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isModerator()
    {
        return $this->hasRole('moderator');
    }

    public function isContentCreator()
    {
        return $this->hasRole('content-creator');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $roleSlug)
    {
        return $query->whereHas('role', function ($q) use ($roleSlug) {
            $q->where('slug', $roleSlug);
        });
    }

    public function scopeWithActiveSubscription($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('subscription_ends_at')
              ->orWhere('subscription_ends_at', '>', now());
        });
    }
}
