<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CastCrew extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cast_crews';

    protected $fillable = [
        'name',
        'slug',
        'bio',
        'photo',
        'type'
    ];

    // Relationships
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_cast_crew')
            ->withPivot('role', 'character_name')
            ->withTimestamps();
    }

    // Scopes
    public function scopeActors($query)
    {
        return $query->where('type', 'actor');
    }

    public function scopeDirectors($query)
    {
        return $query->where('type', 'director');
    }

    public function scopeProducers($query)
    {
        return $query->where('type', 'producer');
    }

    public function scopeWriters($query)
    {
        return $query->where('type', 'writer');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
