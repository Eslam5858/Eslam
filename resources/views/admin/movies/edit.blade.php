@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Edit Movie: {{ $movie->title }}</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
            <input type="text" name="title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('title', $movie->title) }}" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
            <textarea name="description" id="description" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ old('description', $movie->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="release_date" class="block text-gray-700 text-sm font-bold mb-2">Release Date:</label>
            <input type="date" name="release_date" id="release_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('release_date', $movie->release_date) }}" required>
        </div>

        <div class="mb-4">
            <label for="poster_url" class="block text-gray-700 text-sm font-bold mb-2">Poster URL:</label>
            <input type="url" name="poster_url" id="poster_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('poster_url', $movie->poster_url) }}" required>
        </div>

        <div class="mb-4">
            <label for="age_rating" class="block text-gray-700 text-sm font-bold mb-2">Age Rating:</label>
            <input type="number" name="age_rating" id="age_rating" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('age_rating', $movie->age_rating) }}" required min="0">
        </div>

        <div class="mb-4">
            <label for="ticket_price" class="block text-gray-700 text-sm font-bold mb-2">Ticket Price:</label>
            <input type="number" name="ticket_price" id="ticket_price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('ticket_price', $movie->ticket_price) }}" required min="0">
        </div>

        <div class="mb-4">
            <label for="genre" class="block text-gray-700 text-sm font-bold mb-2">Genre:</label>
            <input type="text" name="genre" id="genre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('genre', $movie->genre) }}" required>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Movie
            </button>
            <a href="{{ route('admin.movies') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection 