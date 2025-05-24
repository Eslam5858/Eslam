<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movie Cards</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            background-color: #f3f4f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .card {
            background-color: white;
            width: 250px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card img {
            width: 100%;
            height: 350px;
            object-fit: cover;
        }
        .card-body {
            padding: 15px;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 10px 0;
        }
        .card-text {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }
        .tag {
            display: inline-block;
            background-color: #e5e7eb;
            padding: 4px 8px;
            border-radius: 5px;
            margin-right: 5px;
            font-size: 12px;
            color: #333;
        }
        .book-btn {
            display: inline-block;
            margin-top: 12px;
            padding: 8px 16px;
            background-color: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .book-btn:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>

    <h1 style="text-align: center; margin-bottom: 30px;">🎬 Movie Gallery</h1>

    <div class="grid">
        @foreach ($movies as $movie)
            <div class="card">
                <img src="{{ asset($movie->poster_url) }}" alt="{{ $movie->title }}">
                <div class="card-body">
                    <div class="card-title">{{ $movie->title }}</div>
                    <div class="card-text">{{ Str::limit($movie->description, 80) }}</div>
                    <div>
                        <span class="tag">Released: {{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }}</span>
                        <span class="tag">Age: {{ $movie->age_rating }}+</span>
                        <span class="tag">Price: ${{ number_format($movie->ticket_price, 2) }}</span>
                    </div>
                    <a href="{{ route('movies.book', $movie->id) }}" class="book-btn">Book Now</a>
                </div>
            </div>
        @endforeach
    </div>

</body>
</html> 