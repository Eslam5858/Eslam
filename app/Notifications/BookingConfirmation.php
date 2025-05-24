<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Confirmation - ' . $this->booking->movie->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your booking has been confirmed.')
            ->line('Movie: ' . $this->booking->movie->title)
            ->line('Date: ' . $this->booking->dateShowtime->date->date->format('M d, Y'))
            ->line('Time: ' . $this->booking->dateShowtime->showtime->start_time)
            ->line('Seats: ' . $this->booking->seats->pluck('seat_number')->implode(', '))
            ->line('Total Price: $' . number_format($this->booking->total_price, 2))
            ->action('View Booking', route('bookings.show', $this->booking))
            ->line('Thank you for choosing our cinema!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'movie_title' => $this->booking->movie->title,
            'date' => $this->booking->dateShowtime->date->date->format('M d, Y'),
            'time' => $this->booking->dateShowtime->showtime->start_time,
            'seats' => $this->booking->seats->pluck('seat_number')->implode(', '),
            'total_price' => $this->booking->total_price,
        ];
    }
} 