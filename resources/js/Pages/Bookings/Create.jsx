import React from 'react';
import { Head } from '@inertiajs/react';
import BookingForm from '@/Components/BookingForm';

const Create = ({ movie, date, showtime, seats }) => {
    return (
        <>
            <Head title={`Book ${movie.title}`} />
            <BookingForm
                movie={movie}
                date={date}
                showtime={showtime}
                seats={seats}
            />
        </>
    );
};

export default Create; 