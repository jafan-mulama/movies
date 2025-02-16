<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin() || $this->user()->isContentCreator();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'synopsis' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'], // 2MB max
            'trailer_url' => ['nullable', 'url'],
            'release_year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'director' => ['required', 'string', 'max:255'],
            'genre' => ['required', 'string', 'max:255'],
            'duration' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'in:free,premium'],
            'featured' => ['boolean'],
            'is_active' => ['boolean'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'cast_crew' => ['array'],
            'cast_crew.*.id' => ['required', 'exists:cast_crews,id'],
            'cast_crew.*.role' => ['required', 'string'],
            'cast_crew.*.character_name' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Movie title is required',
            'synopsis.required' => 'Movie synopsis is required',
            'thumbnail.image' => 'Thumbnail must be an image',
            'thumbnail.max' => 'Thumbnail size cannot exceed 2MB',
            'trailer_url.url' => 'Please provide a valid URL for the trailer',
            'release_year.required' => 'Release year is required',
            'release_year.min' => 'Release year cannot be earlier than 1900',
            'release_year.max' => 'Release year cannot be later than ' . (date('Y') + 1),
            'director.required' => 'Director name is required',
            'genre.required' => 'Genre is required',
            'duration.required' => 'Movie duration is required',
            'duration.min' => 'Duration must be at least 1 minute',
            'type.required' => 'Movie type is required',
            'type.in' => 'Movie type must be either free or premium',
            'categories.required' => 'At least one category is required',
            'categories.*.exists' => 'Selected category does not exist',
            'cast_crew.*.id.required' => 'Cast/crew member ID is required',
            'cast_crew.*.id.exists' => 'Selected cast/crew member does not exist',
            'cast_crew.*.role.required' => 'Role is required for each cast/crew member',
        ];
    }
}
