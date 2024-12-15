<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MovieSearchResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public $movie; // Додаємо змінну для фільму

    public function __construct($movie)
    {
        $this->movie = $movie;
    }

    public function build()
    {
        return $this->view('emails.movie_search_result')
            ->with([
                'title' => $this->movie['Title'],
                'year' => $this->movie['Year'],
                'genre' => $this->movie['Genre'],
                'director' => $this->movie['Director'],
                'actors' => $this->movie['Actors'],
                'plot' => $this->movie['Plot'],
                'rating' => $this->movie['imdbRating'],
            ])
            ->subject('Movie Search Result');
    }
}
