<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book {{ $movie->title }}</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            background-color: #f3f4f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #1f2937;
            margin-bottom: 20px;
        }
        .movie-info {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 8px;
        }
        .movie-info p {
            margin: 10px 0;
            color: #4b5563;
        }
        .booking-form {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #374151;
        }
        input, select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 16px;
        }
        button {
            background-color: #2563eb;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Book Tickets for {{ $movie->title }}</h1>
        
        <div class="movie-info">
            <img src="{{ asset($movie->poster_url) }}" alt="{{ $movie->title }}" style="width:180px; border-radius:10px; margin-bottom:15px;">
            <p><strong>Release Date:</strong> {{ \Carbon\Carbon::parse($movie->release_date)->format('F j, Y') }}</p>
            <p><strong>Age Rating:</strong> {{ $movie->age_rating }}+</p>
            <p><strong>Ticket Price:</strong> ${{ number_format($movie->ticket_price, 2) }}</p>
        </div>

        <form action="#" method="POST" class="booking-form">
            @csrf
            <div class="form-group">
                <label for="date">Select Date</label>
                <input type="date" id="date" name="date" required>
            </div>

            <div class="form-group">
                <label for="time">Select Time</label>
                <select id="time" name="time" required>
                    <option value="">Choose a time</option>
                    <option value="10:00">10:00 AM</option>
                    <option value="13:00">1:00 PM</option>
                    <option value="16:00">4:00 PM</option>
                    <option value="19:00">7:00 PM</option>
                    <option value="22:00">10:00 PM</option>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Number of Tickets</label>
                <input type="number" id="quantity" name="quantity" min="1" max="10" value="1" required>
            </div>

            <button type="submit">Confirm Booking</button>
        </form>
    </div>
</body>
</html> 