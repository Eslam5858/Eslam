<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Date;
use App\Models\Movie;
use App\Models\Showtime;
use Carbon\Carbon;

class DateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get all movies
        $movies = Movie::all();

        // get all showtimes
        $showtimes = Showtime::all();

        // get dates from now to two weeks later
        $startDate = Carbon::today();
        $endDate = $startDate->copy()->addWeeks(2);

        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            // Check if the date already exists to avoid duplicates
            $existingDate = Date::whereDate('date', $currentDate->format('Y-m-d'))->first();

            if (!$existingDate) {
                // create date record
                $date = Date::create([
                    'date' => $currentDate->format('Y-m-d'),
                ]);

                // attach date to movies
                $date->movies()->attach($movies);

                // attach date to showtimes
                $date->showtimes()->attach($showtimes);
            }

            $currentDate->addDay();
        }
    }
}
