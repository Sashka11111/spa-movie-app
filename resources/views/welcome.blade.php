<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Search</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="text-center mb-4">Movie Search</h1>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div>
                <input id="movie-title" type="text" class="form-control mb-2" placeholder="Enter movie title" />
                <input id="email" type="email" class="form-control mb-2" placeholder="Enter your email (optional)" />
                <button id="search-button" class="btn btn-primary">Search</button>
            </div>
        </div>
    </div>

    <div id="movie-container" class="row justify-content-center mt-4"></div>
</div>

<!-- JavaScript -->
<script>
    document.getElementById('search-button').addEventListener('click', async function () {
        const title = document.getElementById('movie-title').value;
        const email = document.getElementById('email').value;
        const movieContainer = document.getElementById('movie-container');

        // Очистка попереднього результату
        movieContainer.innerHTML = '';

        if (!title.trim()) {
            movieContainer.innerHTML = '<div class="alert alert-warning">Please enter a movie title.</div>';
            return;
        }

        try {
            const response = await fetch(`/api/search?title=${encodeURIComponent(title)}&email=${encodeURIComponent(email)}`);
            if (!response.ok) {
                throw new Error('Movie not found');
            }

            const movie = await response.json();

            // Відображення результату на сайті
            movieContainer.innerHTML = `
            <div class="col-md-8">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="${movie.Poster}" alt="${movie.Title}" class="img-fluid movie-poster">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">${movie.Title} (${movie.Year})</h5>
                                <p class="card-text"><strong>Genre:</strong> ${movie.Genre}</p>
                                <p class="card-text"><strong>Director:</strong> ${movie.Director}</p>
                                <p class="card-text"><strong>Actors:</strong> ${movie.Actors}</p>
                                <p class="card-text"><strong>Plot:</strong> ${movie.Plot}</p>
                                <p class="card-text"><strong>IMDB Rating:</strong> ${movie.imdbRating}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

            // Відправка листа на вказану електронну адресу, якщо вона є
            if (email.trim()) {
                const emailResponse = await fetch('/api/send-email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email: email,
                        movie: movie,
                    }),
                });
            }

        } catch (error) {
            movieContainer.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
        }
    });
</script>
</body>
</html>
