<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'duration_in_days',
        'features',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_in_days' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper methods
    public function hasFeature($feature)
    {
        return in_array($feature, $this->features ?? []);
    }

    public function getDurationInMonths()
    {
        return round($this->duration_in_days / 30, 1);
    }

    public function getMonthlyPrice()
    {
        $months = $this->getDurationInMonths();
        return $months > 0 ? round($this->price / $months, 2) : $this->price;
    }
}
