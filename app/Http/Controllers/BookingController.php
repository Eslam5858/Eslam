<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\DateShowtime;
use App\Models\Movie;
use App\Models\Date;
use App\Models\Seat;
use App\Models\Showtime;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Notifications\BookingConfirmation;
use App\Notifications\BookingCancellation;
use Inertia\Inertia;

class BookingController extends Controller
{
    /**
     * Create returns the booking page.
     *
     * @param Movie $movie
     * @param Date $date
     * @param Showtime $showtime
     * @return View|RedirectResponse
     */
    public function create(Movie $movie, Date $date, Showtime $showtime): View|RedirectResponse
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to book tickets.');
        }

        // Check if user is old enough to watch the movie
        if (auth()->user()->age < $movie->age_rating) {
            return back()->with('error', 'You are not old enough to watch this movie.');
        }

        // Check if the date and showtime are valid for booking
        $currentDate = now()->format('Y-m-d');
        $currentTime = now()->format('H:i:s');
        
        if ($date->date->format('Y-m-d') < $currentDate) {
            return back()->with('error', 'Cannot book tickets for past dates.');
        }
        
        if ($date->date->format('Y-m-d') == $currentDate && $showtime->start_time < $currentTime) {
            return back()->with('error', 'Cannot book tickets for past showtimes.');
        }

        // Get available seats
        $seats = Seat::whereDoesntHave('bookings', function ($query) use ($date, $showtime) {
            $query->where('date_id', $date->id)
                  ->where('showtime_id', $showtime->id);
        })->get();

        return Inertia::render('Bookings/Create', [
            'movie' => $movie,
            'date' => $date,
            'showtime' => $showtime,
            'seats' => $seats
        ]);
    }

    /**
     * Store the booking.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'date_id' => 'required|exists:dates,id',
            'showtime_id' => 'required|exists:showtimes,id',
            'seats' => 'required|array|min:1',
            'seats.*' => 'exists:seats,id'
        ]);

        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to book tickets.');
        }

        $movie = Movie::findOrFail($request->movie_id);
        $date = Date::findOrFail($request->date_id);
        $showtime = Showtime::findOrFail($request->showtime_id);

        // Check if user is old enough
        if (auth()->user()->age < $movie->age_rating) {
            return back()->with('error', 'You are not old enough to watch this movie.');
        }

        // Check if seats are available
        $bookedSeats = Booking::where('date_id', $date->id)
            ->where('showtime_id', $showtime->id)
            ->whereIn('seat_id', $request->seats)
            ->exists();

        if ($bookedSeats) {
            return back()->with('error', 'One or more selected seats are already booked.');
        }

        // Calculate total price
        $totalPrice = count($request->seats) * $movie->ticket_price;

        // Check if user has enough balance
        if (auth()->user()->balance < $totalPrice) {
            return back()->with('error', 'Insufficient balance. Please top up your account.');
        }

        // Create bookings
        foreach ($request->seats as $seatId) {
            Booking::create([
                'user_id' => auth()->id(),
                'movie_id' => $movie->id,
                'date_id' => $date->id,
                'showtime_id' => $showtime->id,
                'seat_id' => $seatId,
                'total_price' => $movie->ticket_price
            ]);
        }

        // Deduct balance
        auth()->user()->update([
            'balance' => auth()->user()->balance - $totalPrice
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Tickets booked successfully!');
    }

    /**
     * Index returns the booking history page.
     *
     * @return View
     */
    public function index(): View
    {
        $user = User::find(auth()->id());
        $bookings = Booking::where('user_id', $user->id)
            ->with('movie', 'dateShowtime.date', 'dateShowtime.showtime', 'seats')
            ->latest()
            ->paginate(5);

        $currentDate = today('Asia/Jakarta')->format('Y-m-d');
        $currentTime = now('Asia/Jakarta')->format('H:i:s');

        return view('bookings.index', compact('bookings', 'currentDate', 'currentTime'));
    }

    /**
     * Update the booking status.
     *
     * @param Booking $booking
     * @return RedirectResponse
     */
    public function update(Booking $booking): RedirectResponse
    {
        $booking->status = BookingStatus::CANCELLED;
        $booking->update();

        foreach ($booking->seats as $seat) {
            $booking->seats()->detach($seat->id);
        }

        $user = User::find(auth()->id());
        $user->balance += $booking->total_price;
        $user->update();

        // Send booking cancellation notification
        $user->notify(new BookingCancellation($booking));

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking cancelled!');
    }
}
