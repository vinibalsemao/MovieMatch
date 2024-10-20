<?php
session_start();
include_once '../../Model/conecta_bd.php';
include_once '../../Model/api.php';

$genreId = isset($_GET['genre_id']) ? (int)$_GET['genre_id'] : null;
if (!$genreId) {
    die("ID do gênero não fornecido.");
}

function fetchGenreName($genreId)
{
    $apiKey = "daf1bcaad0c418fca4f175ca58a88177";
    $url = "https://api.themoviedb.org/3/genre/movie/list?api_key={$apiKey}&language=pt-BR";

    $response = file_get_contents($url);
    if ($response === FALSE) {
        die("Erro ao acessar a API.");
    }

    $genres = json_decode($response, true)['genres'];

    foreach ($genres as $genre) {
        if ($genre['id'] == $genreId) {
            return $genre['name'];
        }
    }

    return null;
}

$genreName = fetchGenreName($genreId);
if ($genreName === null) {
    die("Gênero não encontrado.");
}

function fetchMoviesByGenre($genreId, $sort)
{
    $apiKey = "daf1bcaad0c418fca4f175ca58a88177";
    $url = "https://api.themoviedb.org/3/discover/movie?api_key={$apiKey}&with_genres={$genreId}&sort_by={$sort}&language=pt-BR&vote_count.gte=50";

    $response = file_get_contents($url);
    if ($response === FALSE) {
        die("Erro ao acessar a API.");
    }

    return json_decode($response, true)['results'];
}

$topRatedMovies = fetchMoviesByGenre($genreId, 'vote_average.desc');
$popularMovies = fetchMoviesByGenre($genreId, 'popularity.desc');

include_once '../style/header.php';
?>

<style>
    #top-rated-movies,
    #popular-movies {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .movie h6 {
        margin: 0.5rem 0;
    }

    .movie p {
        margin: 0;
        color: grey;
    }
</style>

<body>
    <div class="container my-5">
        <div class="py-5 text-center">
            <h1>Filmes de <b style="color: orange"><?= htmlspecialchars($genreName) ?></b></h1>
            <p class="font-italic">Veja e explore os filmes mais bem avaliados e mais populares desse gênero!</p>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <h2>Mais bem avaliados</h2>
                <div class="row" id="top-rated-movies">
                    <?php foreach (array_slice($topRatedMovies, 0, 4) as $movie) : ?>
                        <div class="col-md-3 mb-4 movie">
                            <a href="detalhes_filmes.php?id=<?= $movie['id'] ?>">
                                <img src="https://image.tmdb.org/t/p/w300<?= $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                                <h6><?= htmlspecialchars($movie['title']) ?></h6>
                                <p><?= htmlspecialchars($movie['release_date']) ? date('Y', strtotime($movie['release_date'])) : 'Data não informada' ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($topRatedMovies) > 4) : ?>
                    <div class="text-center">
                        <button id="show-more-top-rated" class="btn btn-dark" style="width: 1110px;">Ver mais</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <h2>Mais populares</h2>
                <div class="row" id="popular-movies">
                    <?php foreach (array_slice($popularMovies, 0, 4) as $movie) : ?>
                        <div class="col-md-3 mb-4 movie">
                            <a href="detalhes_filmes.php?id=<?= $movie['id'] ?>">
                                <img src="https://image.tmdb.org/t/p/w300<?= $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                                <h6><?= htmlspecialchars($movie['title']) ?></h6>
                                <p><?= htmlspecialchars($movie['release_date']) ? date('Y', strtotime($movie['release_date'])) : 'Data não informada' ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($popularMovies) > 4) : ?>
                    <div class="text-center">
                        <button id="show-more-popular" class="btn btn-dark" style="width: 1110px;">Ver mais</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('show-more-top-rated').addEventListener('click', function() {
            var container = document.getElementById('top-rated-movies');
            var hiddenMovies = <?= json_encode(array_slice($topRatedMovies, 8)) ?>;
            hiddenMovies.forEach(function(movie) {
                var movieDiv = document.createElement('div');
                movieDiv.classList.add('col-md-3', 'mb-4', 'movie');
                movieDiv.innerHTML = '<a href="detalhes_filmes.php?id=' + movie.id + '">' +
                    '<img src="https://image.tmdb.org/t/p/w300' + movie.poster_path + '" alt="' + movie.title + '">' +
                    '<h6>' + movie.title + '</h6>' +
                    '<p>' + (movie.release_date ? new Date(movie.release_date).getFullYear() : 'Data não informada') + '</p>' +
                    '</a>';
                container.appendChild(movieDiv);
            });
            this.style.display = 'none';
        });

        document.getElementById('show-more-popular').addEventListener('click', function() {
            var container = document.getElementById('popular-movies');
            var hiddenMovies = <?= json_encode(array_slice($popularMovies, 8)) ?>;
            hiddenMovies.forEach(function(movie) {
                var movieDiv = document.createElement('div');
                movieDiv.classList.add('col-md-3', 'mb-4', 'movie');
                movieDiv.innerHTML = '<a href="detalhes_filmes.php?id=' + movie.id + '">' +
                    '<img src="https://image.tmdb.org/t/p/w300' + movie.poster_path + '" alt="' + movie.title + '">' +
                    '<h6>' + movie.title + '</h6>' +
                    '<p>' + (movie.release_date ? new Date(movie.release_date).getFullYear() : 'Data não informada') + '</p>' +
                    '</a>';
                container.appendChild(movieDiv);
            });
            this.style.display = 'none';
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>