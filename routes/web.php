<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminMovieController;
use Illuminate\Support\Facades\Route;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;
use App\Enums\BookingStatus;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [MovieController::class, 'index'])->name('home');
Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');

Route::middleware('guest')->group(function () {
    Route::get('/register', [UserController::class, 'create'])->name('register');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/login', [UserController::class, 'login'])->name('login');
    Route::post('/login', [UserController::class, 'auth'])->name('auth');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    Route::get('/movies/{movie}/book/{date}/{showtime}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/movies/{movie}/book/{date}/{showtime}', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/movies', [AdminController::class, 'movies'])->name('admin.movies');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');

    // Movie management routes
    Route::get('/movies/create', [AdminMovieController::class, 'create'])->name('admin.movies.create');
    Route::post('/movies', [AdminMovieController::class, 'store'])->name('admin.movies.store');
    Route::get('/movies/{movie}/edit', [AdminMovieController::class, 'edit'])->name('admin.movies.edit');
    Route::put('/movies/{movie}', [AdminMovieController::class, 'update'])->name('admin.movies.update');
    Route::delete('/movies/{movie}', [AdminMovieController::class, 'destroy'])->name('admin.movies.destroy');
});

Route::get('/movies/{movie}/book', function (\App\Models\Movie $movie) {
    $bookedSeats = DB::table('booked_seats')
        ->join('bookings', 'booked_seats.booking_id', '=', 'bookings.id')
        ->where('bookings.movie_id', $movie->id)
        ->pluck('booked_seats.seat_id')
        ->toArray();

    return view('movies.seats', compact('movie', 'bookedSeats'));
})->name('movies.book');

Route::post('/movies/{movie}/book', function (\Illuminate\Http\Request $request, \App\Models\Movie $movie) {
    // التحقق من وجود $movie
    if (!$movie) {
        return redirect()->back()->with('error', 'Movie not found');
    }

    $seats = explode(',', $request->input('seats'));

    // للتأكد من البيانات
    \Log::info('Booking attempt', [
        'movie_id' => $movie->id,
        'seats' => $seats
    ]);

    $booking = \App\Models\Booking::create([
        'movie_id' => $movie->id,
        'user_id' => auth()->id() ?? 1,
        'date_showtime_id' => 1,
        'total_price' => count($seats) * $movie->ticket_price,
        'status' => BookingStatus::PAID, // نستخدم PAID لأنه موجود في Enum
    ]);

    foreach ($seats as $seatId) {
        DB::table('booked_seats')->insert([
            'booking_id' => $booking->id,
            'seat_id' => trim($seatId),
            'date_showtime_id' => 1,
        ]);
    }

    return redirect()->route('movies.book', $movie->id)
        ->with('success', 'Booking completed!');
})->name('movies.book.store');

Route::get('/movies', function () {
    $movies = Movie::all();
    return view('movies.index', compact('movies'));
});
