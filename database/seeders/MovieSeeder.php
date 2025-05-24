<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::get('https://seleksi-sea-2023.vercel.app/api/movies');
        $movies = $response->json();

        // Check if the response is valid
        if (!is_array($movies)) {
            $this->command->error('Failed to fetch movies from API. API not available.');
            
            // Define default movies
            $defaultMovies = [
                [
                    'title' => 'The Shawshank Redemption',
                    'description' => 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
                    'release_date' => '1994-09-23',
                    'poster_url' => 'https://example.com/shawshank.jpg',
                    'age_rating' => 13,
                    'ticket_price' => 5,
                    'genre' => 'Drama',
                ],
                [
                    'title' => 'The Dark Knight',
                    'description' => 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.',
                    'release_date' => '2008-07-18',
                    'poster_url' => 'https://example.com/darkknight.jpg',
                    'age_rating' => 13,
                    'ticket_price' => 7,
                    'genre' => 'Action',
                ],
                [
                    'title' => 'Inception',
                    'description' => 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.',
                    'release_date' => '2010-07-16',
                    'poster_url' => 'https://example.com/inception.jpg',
                    'age_rating' => 13,
                    'ticket_price' => 8,
                    'genre' => 'Sci-Fi',
                ],
                [
                    'title' => 'The Matrix',
                    'description' => 'A computer programmer discovers that reality as we know it is simply a simulation created by sentient machines, and joins a rebellion against them.',
                    'release_date' => '1999-03-31',
                    'poster_url' => 'https://example.com/matrix.jpg',
                    'age_rating' => 13,
                    'ticket_price' => 6,
                    'genre' => 'Sci-Fi',
                ],
                [
                    'title' => 'Interstellar',
                    'description' => "A team of explorers travel through a wormhole in space in an attempt to ensure humanity's survival.",
                    'release_date' => '2014-11-07',
                    'poster_url' => 'https://example.com/interstellar.jpg',
                    'age_rating' => 13,
                    'ticket_price' => 9,
                    'genre' => 'Sci-Fi',
                ],
            ];

            $moviesToSeed = $defaultMovies;
            $source = 'default movies';
        } else {
            $moviesToSeed = $movies;
            $source = 'API';
        }

        $this->command->info("Seeding movies from {$source}...");

        foreach ($moviesToSeed as $movieData) {
            // Check if a movie with this title already exists
            $existingMovie = Movie::where('title', $movieData['title'])->first();

            if ($existingMovie) {
                $this->command->warn("Movie '".$movieData['title']."' already exists. Skipping.");
                continue;
            }

            // Handle missing genre for API movies if necessary, defaulting to Action
            $genre = $movieData['genre'] ?? 'Action';

            Movie::create([
                'title' => $movieData['title'],
                'description' => $movieData['description'] ?? 'No description available.', // Provide a default description
                'release_date' => $movieData['release_date'] ?? now()->format('Y-m-d'), // Provide a default date
                'poster_url' => $movieData['poster_url'] ?? 'https://via.placeholder.com/150', // Provide a default poster
                'age_rating' => $movieData['age_rating'] ?? 0, // Provide a default age rating
                'ticket_price' => $movieData['ticket_price'] ?? 0, // Provide a default price
                'genre' => $genre,
            ]);
        }

        $this->command->info('Movie seeding completed.');
    }
}
