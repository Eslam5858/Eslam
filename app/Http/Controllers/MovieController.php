<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class MovieController extends Controller
{
    /**
     * Index returns the home page.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $title = request()->input('search');
        $sort = request()->input('sort');

        $movies = Movie::filter($title, $sort)
            ->latest()
            ->paginate(8);

        return view('movies.home', compact('movies'));
    }

    /**
     * Show returns the movie detail page.
     *
     * @param Movie $movie
     * @return View
     */
    public function show(Movie $movie): View
    {
        $currentDate = today('Asia/Jakarta')->format('Y-m-d');
        $currentTime = now('Asia/Jakarta')->format('H:i:s');

        // Load dates for the current week and associate showtimes
        $movie->load([
            'dates' => function ($query) use ($currentDate) {
                $query->where('date', '>=', $currentDate)
                      ->with('showtimes');
            }
        ]);

        // Pass the current timestamp to the view
        $currentTimestamp = now('Asia/Jakarta');

        return view('movies.show', compact('movie', 'currentDate', 'currentTime', 'currentTimestamp'));
    }
}
