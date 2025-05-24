import React, { useState, useEffect } from 'react';
import { useForm } from '@inertiajs/react';

const BookingForm = ({ movie, date, showtime, seats }) => {
    const [selectedSeats, setSelectedSeats] = useState([]);
    const [totalPrice, setTotalPrice] = useState(0);

    const { data, setData, post, processing, errors } = useForm({
        movie_id: movie.id,
        date_id: date.id,
        showtime_id: showtime.id,
        seats: []
    });

    useEffect(() => {
        setTotalPrice(selectedSeats.length * movie.ticket_price);
    }, [selectedSeats, movie.ticket_price]);

    const handleSeatSelection = (seatId) => {
        setSelectedSeats(prev => {
            if (prev.includes(seatId)) {
                return prev.filter(id => id !== seatId);
            } else {
                return [...prev, seatId];
            }
        });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setData('seats', selectedSeats);
        post(route('bookings.store'));
    };

    return (
        <div className="container mx-auto px-4 py-8">
            <div className="max-w-4xl mx-auto">
                <h1 className="text-3xl font-bold mb-8">Book Tickets for {movie.title}</h1>

                <div className="bg-white rounded-lg shadow-lg p-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <img src={movie.poster_url} alt={movie.title} className="w-full rounded-lg" />
                        </div>
                        <div>
                            <h2 className="text-2xl font-semibold mb-4">{movie.title}</h2>
                            <p className="text-gray-600 mb-4">{movie.description}</p>
                            <div className="mb-4">
                                <p><strong>Date:</strong> {new Date(date.date).toLocaleDateString()}</p>
                                <p><strong>Showtime:</strong> {showtime.start_time} - {showtime.end_time}</p>
                                <p><strong>Price per ticket:</strong> Rp {movie.ticket_price.toLocaleString()}</p>
                                <p><strong>Age Rating:</strong> {movie.age_rating}+</p>
                            </div>

                            <form onSubmit={handleSubmit}>
                                <div className="mb-6">
                                    <h3 className="text-xl font-semibold mb-4">Select Your Seats</h3>
                                    <p className="text-gray-600 mb-4">Available seats are shown in green, occupied seats are in red.</p>
                                    
                                    <div className="grid grid-cols-8 gap-2 mb-4">
                                        {seats.map(seat => (
                                            <div key={seat.id} className="relative">
                                                <input
                                                    type="checkbox"
                                                    id={`seat-${seat.id}`}
                                                    className="hidden peer"
                                                    checked={selectedSeats.includes(seat.id)}
                                                    onChange={() => handleSeatSelection(seat.id)}
                                                    disabled={seat.is_booked}
                                                />
                                                <label
                                                    htmlFor={`seat-${seat.id}`}
                                                    className={`block w-full h-8 rounded-lg cursor-pointer
                                                        ${seat.is_booked 
                                                            ? 'bg-red-500 cursor-not-allowed' 
                                                            : 'bg-green-500 hover:bg-green-600 peer-checked:bg-blue-500'}`}
                                                >
                                                    <span className="sr-only">Seat {seat.seat_number}</span>
                                                </label>
                                                <span className="absolute -bottom-6 left-1/2 transform -translate-x-1/2 text-xs">
                                                    {seat.seat_number}
                                                </span>
                                            </div>
                                        ))}
                                    </div>

                                    <div className="mt-8 p-4 bg-gray-50 rounded-lg">
                                        <h4 className="font-semibold mb-2">Price Summary</h4>
                                        <p>Selected Seats: {selectedSeats.length}</p>
                                        <p>Total Price: Rp {totalPrice.toLocaleString()}</p>
                                    </div>
                                </div>

                                <div className="flex justify-end">
                                    <button
                                        type="submit"
                                        disabled={processing || selectedSeats.length === 0}
                                        className="bg-primary-500 text-white px-6 py-2 rounded-lg hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {processing ? 'Processing...' : 'Book Now'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default BookingForm; 