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
                    'poster_url' => 'image/The Shawshank Redemption.jpeg',
                    'age_rating' => 15,
                    'ticket_price' => 40,
                    'trailer_url' => 'https://www.youtube.com/watch?v=6hB3S9bIaco',
                    'genre' => 'Drama',
                ],
                [
                    'title' => 'The Dark Knight',
                    'description' => 'Batman faces his toughest opponent yet: the Joker, a criminal mastermind who wants to see Gotham fall.',
                    'release_date' => '2008-07-18',
                    'poster_url' => 'image/The Dark Knight.jpeg',
                    'age_rating' => 14,
                    'ticket_price' => 55,
                    'trailer_url' => 'https://www.youtube.com/watch?v=EXeTwQWrcwY',
                    'genre' => 'Action',
                ],
                [
                    'title' => 'Inception',
                    'description' => 'A skilled thief is offered a chance to erase his criminal record by infiltrating dreams.',
                    'release_date' => '2010-07-16',
                    'poster_url' => 'image/Inception.jpg',
                    'age_rating' => 13,
                    'ticket_price' => 50,
                    'trailer_url' => 'https://www.youtube.com/watch?v=YoHD9XEInc0',
                    'genre' => 'Sci-Fi',
                ],
                [
                    'title' => 'The Matrix',
                    'description' => 'A computer hacker learns about the true nature of reality and his role in the war against its controllers.',
                    'release_date' => '1999-03-31',
                    'poster_url' => 'image/The Matrix.jpg',
                    'age_rating' => 16,
                    'ticket_price' => 45,
                    'trailer_url' => 'https://www.youtube.com/watch?v=vKQi3bBA1y8',
                    'genre' => 'Sci-Fi',
                ],
                [
                    'title' => 'Interstellar',
                    'description' => 'A team of explorers travels through a wormhole in space in an attempt to ensure humanity\'s survival.',
                    'release_date' => '2014-11-07',
                    'poster_url' => 'image/Interstellar.jpg',
                    'age_rating' => 13,
                    'ticket_price' => 60,
                    'trailer_url' => 'https://www.youtube.com/watch?v=zSWdZVtXT7E',
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
                'trailer_url' => $movieData['trailer_url'] ?? null,
                'genre' => $genre,
            ]);
        }

        $this->command->info('Movie seeding completed.');
    }
}
