<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\StoreMovieRequest;
use App\Http\Requests\Movie\UpdateMovieRequest;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Movie::class, 'movie');
    }

    public function index(Request $request)
    {
        $query = Movie::query()
            ->with(['categories', 'castCrew'])
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('synopsis', 'like', "%{$search}%");
            })
            ->when($request->category, function ($query, $category) {
                $query->whereHas('categories', function ($query) use ($category) {
                    $query->where('categories.id', $category);
                });
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            });

        return $query->latest()->paginate($request->per_page ?? 15);
    }

    public function store(StoreMovieRequest $request)
    {
        $movie = Movie::create($request->safe()->except(['categories', 'cast_crew']));

        if ($request->categories) {
            $movie->categories()->sync($request->categories);
        }

        if ($request->cast_crew) {
            $movie->castCrew()->sync(collect($request->cast_crew)->mapWithKeys(function ($item) {
                return [$item['id'] => [
                    'role' => $item['role'],
                    'character_name' => $item['character_name'] ?? null,
                ]];
            }));
        }

        return response()->json([
            'message' => 'Movie created successfully',
            'movie' => $movie->load(['categories', 'castCrew']),
        ], 201);
    }

    public function show(Movie $movie)
    {
        return $movie->load(['categories', 'castCrew', 'reviews' => function ($query) {
            $query->approved()->latest();
        }]);
    }

    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        $movie->update($request->safe()->except(['categories', 'cast_crew']));

        if ($request->has('categories')) {
            $movie->categories()->sync($request->categories);
        }

        if ($request->has('cast_crew')) {
            $movie->castCrew()->sync(collect($request->cast_crew)->mapWithKeys(function ($item) {
                return [$item['id'] => [
                    'role' => $item['role'],
                    'character_name' => $item['character_name'] ?? null,
                ]];
            }));
        }

        return response()->json([
            'message' => 'Movie updated successfully',
            'movie' => $movie->load(['categories', 'castCrew']),
        ]);
    }

    public function destroy(Movie $movie)
    {
        if ($movie->thumbnail) {
            Storage::disk('public')->delete($movie->thumbnail);
        }

        $movie->delete();

        return response()->json([
            'message' => 'Movie deleted successfully',
        ]);
    }

    public function uploadThumbnail(Request $request, Movie $movie)
    {
        $this->authorize('update', $movie);

        $request->validate([
            'thumbnail' => ['required', 'image', 'max:2048'],
        ]);

        if ($movie->thumbnail) {
            Storage::disk('public')->delete($movie->thumbnail);
        }

        $path = $request->file('thumbnail')->store('thumbnails', 'public');
        $movie->update(['thumbnail' => $path]);

        return response()->json([
            'message' => 'Thumbnail uploaded successfully',
            'thumbnail_url' => Storage::url($path),
        ]);
    }

    public function featured()
    {
        return Movie::featured()
            ->with(['categories', 'castCrew'])
            ->latest()
            ->take(10)
            ->get();
    }

    public function latest()
    {
        return Movie::with(['categories', 'castCrew'])
            ->latest()
            ->take(10)
            ->get();
    }

    public function popular()
    {
        return Movie::withCount('reviews')
            ->with(['categories', 'castCrew'])
            ->orderByDesc('reviews_count')
            ->take(10)
            ->get();
    }
}
