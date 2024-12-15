<?php
namespace App\Http\Controllers;

use App\Mail\MovieSearchResultMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class MovieController extends Controller
{
    public function search(Request $request)
    {
        $movieName = $request->input('title');
        $apiKey = env('OMDB_API_KEY');
        $response = Http::get("http://www.omdbapi.com/", [
            'apikey' => $apiKey,
            't' => $movieName,
        ]);

        if ($response->successful()) {
            // Отримуємо дані фільму
            $movie = $response->json();

            // Перевіряємо, чи є електронна адреса
            $email = $request->input('email');
            if ($email) {
                // Надсилаємо листа
                Mail::to($email)->send(new MovieSearchResultMail($movie));
            }

            return response()->json($movie);
        }

        return response()->json(['error' => 'Movie not found'], 404);
    }
}
