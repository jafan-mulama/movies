<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'synopsis',
        'thumbnail',
        'trailer_url',
        'release_year',
        'director',
        'genre',
        'duration',
        'type',
        'featured',
        'is_active',
        'rating',
        'views'
    ];

    protected $casts = [
        'release_year' => 'integer',
        'duration' => 'integer',
        'featured' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'float',
        'views' => 'integer',
    ];

    // Relationships
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function castAndCrew()
    {
        return $this->belongsToMany(CastCrew::class, 'movie_cast_crew')
            ->withPivot('role', 'character_name')
            ->withTimestamps();
    }

    // Scopes
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors & Mutators
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}
