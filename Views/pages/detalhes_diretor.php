<?php
session_start();
include_once '../../Model/conecta_bd.php';
include_once '../../Model/api.php';

$directorId = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$directorId) {
    die("ID do diretor não fornecido.");
}

function fetchDirectorDetails($directorId)
{
    $apiKey = "daf1bcaad0c418fca4f175ca58a88177";
    $url = "https://api.themoviedb.org/3/person/{$directorId}?api_key={$apiKey}&language=pt-BR";

    $response = file_get_contents($url);
    if ($response === FALSE) {
        die("Erro ao acessar a API.");
    }

    return json_decode($response, true);
}

function fetchDirectorMovies($directorId)
{
    $apiKey = "daf1bcaad0c418fca4f175ca58a88177";
    $url = "https://api.themoviedb.org/3/person/{$directorId}/movie_credits?api_key={$apiKey}&language=pt-BR";

    $response = file_get_contents($url);
    if ($response === FALSE) {
        die("Erro ao acessar a API.");
    }

    return json_decode($response, true);
}

function formatDate($date)
{
    return date('M - Y', strtotime($date));
}

$directorDetails = fetchDirectorDetails($directorId);
$directorMovies = fetchDirectorMovies($directorId);

$movies = [];
foreach ($directorMovies['crew'] as $movie) {
    if ($movie['job'] == 'Director') {
        if (!isset($movies[$movie['id']])) {
            $movies[$movie['id']] = [
                'id' => $movie['id'], 
                'title' => $movie['title'],
                'poster_path' => $movie['poster_path'],
                'release_date' => $movie['release_date'],
                'vote_average' => $movie['vote_average'],
                'jobs' => []
            ];
        }
        $movies[$movie['id']]['jobs'][] = $movie['job'];
    }
}

usort($movies, function ($a, $b) {
    return $b['vote_average'] <=> $a['vote_average'];
});

include_once '../style/header.php';
?>

<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-4">
                <img src="https://image.tmdb.org/t/p/w300<?= $directorDetails['profile_path'] ?>" alt="<?= htmlspecialchars($directorDetails['name']) ?>" class="img-fluid rounded"> 
                </div>
            <div class="col-md-8">
                <h2><?= htmlspecialchars($directorDetails['name']) ?></h2>

                <hr style="background-color: orange;">
                <p>
                    <strong>Biografia:</strong> <?= htmlspecialchars($directorDetails['biography']) ?: "Não informada" ?>

                    <br><br>

                    <strong>Data de Nascimento:</strong> <?= $directorDetails['birthday'] ? formatDate($directorDetails['birthday']) : "Não informada" ?>
                    <br>
                    <strong>Local de Nascimento:</strong> <?= htmlspecialchars($directorDetails['place_of_birth']) ?: "Não informado" ?>
                </p>
            </div>
        </div>

        <hr>

        <h3>Filmografia</h3>
        <div class="row">
            <?php foreach ($movies as $movie) : ?>
                <div class="col-md-3">
                    <div class="movie">
                        <a href="detalhes_filmes.php?id=<?= $movie['id'] ?>">
                            <img src="<?php
                                        if (empty($movie['poster_path'])) {
                                            echo '../uploads/poster.png';
                                        } else {
                                            echo 'https://image.tmdb.org/t/p/w300' . $movie['poster_path'];
                                        }  ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                            <h6><?= htmlspecialchars($movie['title']) ?></h6>
                            
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>