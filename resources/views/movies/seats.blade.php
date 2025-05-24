<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Seats - {{ $movie->title }}</title>
    <style>
        body {
            background-color: #111;
            color: #fff;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
        }
        .movie-info {
            display: flex;
            gap: 20px;
            align-items: center;
            margin-bottom: 20px;
        }
        .movie-info img {
            width: 180px;
            border-radius: 10px;
        }
        .legend {
            display: flex;
            gap: 15px;
            margin-top: 10px;
            font-size: 14px;
        }
        .legend div {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .seat {
            width: 40px;
            height: 40px;
            background-color: grey;
            text-align: center;
            line-height: 40px;
            border-radius: 5px;
            cursor: pointer;
        }
        .seat.selected {
            background-color: limegreen;
        }
        .seat.booked {
            background-color: red;
            cursor: not-allowed;
        }
        .screen {
            background: #444;
            color: #eee;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .seat-grid {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 10px;
        }
        .proceed-btn {
            margin-top: 20px;
            padding: 12px 24px;
            background-color: orange;
            color: black;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        .proceed-btn:disabled {
            background-color: #555;
            color: #999;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>✨ Book Seats for: {{ $movie->title }}</h1>
    
    {{-- اختبار وجود bookedSeats --}}
    <p style="color: limegreen; text-align: center; margin: 20px 0; padding: 10px; background: #1a1a1a; border-radius: 5px;">
        ✅ Seat grid should appear below.
    </p>
    <pre style="color: #fff; background: #1a1a1a; padding: 10px; border-radius: 5px; margin: 10px 0;">
        bookedSeats: {{ print_r($bookedSeats, true) }}
    </pre>

    <div class="movie-info">
        <img src="{{ asset($movie->poster_url) }}" alt="{{ $movie->title }}">
        <div>
            <p><strong>Release Date:</strong> {{ $movie->release_date }}</p>
            <p><strong>Age Rating:</strong> {{ $movie->age_rating }}+</p>
            <p><strong>Price:</strong> ${{ number_format($movie->ticket_price, 2) }}</p>
        </div>
    </div>

    <form action="{{ route('movies.book.store', $movie->id) }}" method="POST">
        @csrf
        <input type="hidden" name="seats" id="selected-seats">

        <div class="screen">SCREEN</div>

        {{-- اختبار ظهور المقاعد --}}
        <div style="color: #fff; background: #1a1a1a; padding: 10px; border-radius: 5px; margin: 10px 0;">
            <p>اختبار ظهور المقاعد:</p>
            @for ($i = 1; $i <= 50; $i++)
                @php $seat = 'S' . $i; @endphp
                <div style="display: inline-block; margin: 2px;">{{ $seat }}</div>
            @endfor
        </div>

        <div class="seat-grid">
            @for ($i = 1; $i <= 50; $i++)
                <div class="seat @if(in_array($i, $bookedSeats)) booked @endif" data-seat="{{ $i }}">{{ $i }}</div>
            @endfor
        </div>

        <div class="legend">
            <div><div class="seat"></div> Available</div>
            <div><div class="seat selected"></div> Selected</div>
            <div><div class="seat booked"></div> Booked</div>
        </div>

        <p style="margin-top: 10px;">Selected Seats: <span id="count">0</span></p>

        <button type="submit" class="proceed-btn" id="book-btn" disabled>Book Now</button>
    </form>
</div>

<script>
    const seats = document.querySelectorAll('.seat:not(.booked)');
    const selectedSeatsInput = document.getElementById('selected-seats');
    const bookBtn = document.getElementById('book-btn');
    const count = document.getElementById('count');
    let selectedSeats = [];

    seats.forEach(seat => {
        seat.addEventListener('click', () => {
            seat.classList.toggle('selected');
            const seatNum = seat.getAttribute('data-seat');

            if (selectedSeats.includes(seatNum)) {
                selectedSeats = selectedSeats.filter(s => s !== seatNum);
            } else {
                selectedSeats.push(seatNum);
            }

            selectedSeatsInput.value = selectedSeats.join(',');
            count.textContent = selectedSeats.length;
            bookBtn.disabled = selectedSeats.length === 0;
        });
    });
</script>

</body>
</html> 