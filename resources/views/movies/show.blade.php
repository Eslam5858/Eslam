@extends('layouts.app')

@section('content')
    <!-- Movie details section -->
    <section id="movie-details" class="p-6 max-w-screen-lg mx-auto">
        {{-- Using a dark background similar to the screenshot --}}
        <div
            class="flex flex-col items-center justify-start bg-gray-900 border border-gray-700 rounded-lg shadow-lg w-full max-w-screen-lg mx-auto md:flex-row md:max-w-7xl text-white overflow-hidden">
            
            {{-- Image on the left --}}
            <img class="object-cover w-full md:w-1/2 rounded-t-lg md:rounded-none md:rounded-l-lg" src="{{ asset($movie->poster_url) }}"
                alt="{{ $movie->title }}" />

            {{-- Details on the right --}}
            <div class="flex flex-col justify-between p-6 leading-normal w-full md:w-1/2">
                <h5 class="mb-4 text-4xl font-bold tracking-tight text-white">
                    {{ $movie->title }}
                </h5>
                {{-- Assuming x-movie-info displays the tags like Released, Age Rating, Price --}}
                <div class="flex flex-wrap my-3">
                    <x-movie-info :movie="$movie" />
                </div>
                <p class="mb-4 font-normal text-gray-300">
                    {{ $movie->description }}
                </p>
                
                <!-- زر الحجز -->
                <div class="mt- auto">
                    <a href="{{ route('movies.book', $movie->id) }}" 
                       class="inline-block bg-yellow-600 text-black px-6 py-3 rounded-lg hover:bg-yellow-700 transition-colors duration-300 font-bold text-center">
                        احجز الآن
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Dates and showtimes list --}}
    <section id="dates-showtimes" class="p-6 max-w-screen-lg mx-auto">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold mb-4 text-center text-white">
                Dates and Showtimes
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
{{--                @foreach ($movie->dates as $date)--}}
{{--                    <x-date-card :date="$date">--}}
{{--                        @foreach ($date->showtimes as $showtime)--}}
{{--                            <x-showtime-button :showtime="$showtime" :movie="$movie" :date="$date" :currentDate="$currentDate"--}}
{{--                                :currentTime="$currentTime" :currentTimestamp="$currentTimestamp" />--}}
{{--                        @endforeach--}}
{{--                    </x-date-card>--}}
{{--                @endforeach--}}
            </div>
        </div>
    </section>
@endsection
