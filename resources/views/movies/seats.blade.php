@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-gray-900 rounded-md text-white">
    <h1 class="text-3xl mb-4">{{ $movie->title }} - Seat Booking</h1>

    @if(session('success'))
        <div class="bg-green-600 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="movie-info flex gap-6 mb-8">
        <img src="{{ asset($movie->poster_url) }}" alt="{{ $movie->title }}" class="w-48 rounded-lg">
        <div>
            <h2 class="text-2xl font-bold mb-2">{{ $movie->title }}</h2>
            <p class="mb-2"><strong>Release:</strong> {{ \Carbon\Carbon::parse($movie->release_date)->format('F j, Y') }}</p>
            <p class="mb-2"><strong>Age Rating:</strong> {{ $movie->age_rating }}+</p>
            <p class="mb-2"><strong>Price:</strong> ${{ number_format($movie->ticket_price, 2) }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('movies.book.store', $movie->id) }}">
        @csrf
        <div class="screen bg-gray-700 text-center py-4 mb-8 rounded-lg">
            SCREEN
        </div>

        <div id="seat-grid" class="grid grid-cols-10 gap-2 mb-8">
            @for($i = 1; $i <= 50; $i++)
                @php
                    $isBooked = in_array($i, $bookedSeats);
                @endphp
                <div
                    class="seat cursor-pointer border rounded p-3 text-center select-none
                        {{ $isBooked ? 'bg-red-600 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700' }}"
                    data-seat="{{ $i }}"
                    @if($isBooked) style="pointer-events:none;" @endif
                >
                    S{{ $i }}
                </div>
            @endfor
        </div>

        <input type="hidden" name="seats" id="selected-seats" />

        <div class="mb-4 text-lg">
            Selected Seats: <span id="selected-count" class="font-bold">0</span>
        </div>

        <button type="submit" id="book-btn" disabled
            class="bg-yellow-600 text-black font-bold py-3 px-8 rounded-lg hover:bg-yellow-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-300">
            Book Now
        </button>
    </form>
</div>

<script>
    const seatGrid = document.getElementById('seat-grid');
    const selectedSeatsInput = document.getElementById('selected-seats');
    const selectedCountSpan = document.getElementById('selected-count');
    const bookBtn = document.getElementById('book-btn');

    let selectedSeats = [];

    seatGrid.addEventListener('click', function(e) {
        if (!e.target.classList.contains('seat') || e.target.classList.contains('cursor-not-allowed')) return;

        const seatNumber = e.target.getAttribute('data-seat');
        const index = selectedSeats.indexOf(seatNumber);

        if (index > -1) {
            // Deselect
            selectedSeats.splice(index, 1);
            e.target.classList.remove('bg-yellow-500');
            e.target.classList.add('bg-green-600');
        } else {
            // Select
            selectedSeats.push(seatNumber);
            e.target.classList.remove('bg-green-600');
            e.target.classList.add('bg-yellow-500');
        }

        selectedSeatsInput.value = selectedSeats.join(',');
        selectedCountSpan.textContent = selectedSeats.length;

        bookBtn.disabled = selectedSeats.length === 0;
    });
</script>
@endsection 