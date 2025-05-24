<div class="movie-card bg-white rounded-lg shadow-lg overflow-hidden">
    <img src="{{ asset($movie->poster_url) }}" 
         alt="{{ $movie->title }}" 
         style="width:100%; height:300px; object-fit:cover; border-radius: 10px;">
    <div style="padding: 10px;">
        <h2 class="text-xl font-semibold mb-2">{{ $movie->title }}</h2>
        <p class="text-gray-600 mb-2">{{ Str::limit($movie->description, 100) }}</p>
        <p class="text-sm text-gray-500"><strong>Released:</strong> {{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }}</p>
        <p class="text-sm text-gray-500"><strong>Age Rating:</strong> {{ $movie->age_rating }}+</p>
        <p class="text-sm text-gray-500"><strong>Price:</strong> ${{ number_format($movie->ticket_price, 2) }}</p>

        <div class="mt-4">
            <a href="{{ route('movies.show', $movie->id) }}" 
               class="inline-block bg-yellow-600 text-black px-4 py-2 rounded hover:bg-yellow-700 transition">
                Book Now
            </a>
    </div>
    </div>
</div>
