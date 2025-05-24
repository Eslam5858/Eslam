@props(['movie', 'date', 'showtime', 'currentDate', 'currentTime', 'currentTimestamp'])

@php
    // Combine date and showtime to create a full timestamp
    $showtimeTimestamp = \Carbon\Carbon::parse($date->date->format('Y-m-d') . ' ' . $showtime->start_time, 'Asia/Jakarta');

    // Check if the showtime is in the past relative to the current timestamp
    $disabled = $showtimeTimestamp->isPast($currentTimestamp);
@endphp

<li>
    @auth
        <a href="{{ route('bookings.create', [$movie, $date, $showtime]) }}">
            <button type="button"
                class="focus:outline-none text-white bg-primary-500 hover:bg-primary-600 focus:ring-4 focus:ring-primary-300 font-semibold rounded-lg text-sm px-5 py-2.5 mr-2 mb-2
                {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                {{ $disabled ? 'disabled' : '' }}>
                {{ $showtime->start_time }} - {{ $showtime->end_time }}
            </button>
        </a>
    @else
        <a href="{{ route('login') }}">
            <button type="button"
                class="focus:outline-none text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-xs px-5 py-2.5 mr-2 mb-2">
                Login to Book
            </button>
        </a>
    @endauth
</li>
