<?php
session_start();
include_once '../../Model/conecta_bd.php';

function fetchMovieDetails($movieId)
{
    $apiKey = "daf1bcaad0c418fca4f175ca58a88177";
    $url = "https://api.themoviedb.org/3/movie/{$movieId}?api_key={$apiKey}&language=pt-BR";

    $response = file_get_contents($url);
    if ($response === FALSE) {
        die("Erro ao acessar a API.");
    }

    return json_decode($response, true);
}

function fetchMovieCast($movieId)
{
    $apiKey = "daf1bcaad0c418fca4f175ca58a88177";
    $url = "https://api.themoviedb.org/3/movie/{$movieId}/credits?api_key={$apiKey}&language=pt-BR";

    $response = file_get_contents($url);
    if ($response === FALSE) {
        die("Erro ao acessar a API.");
    }

    return json_decode($response, true)['cast'];
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
    return date('d M Y', strtotime($date));
}

function fetchSimilarGenreMovies($genreIds, $countryCode)
{
    $apiKey = "daf1bcaad0c418fca4f175ca58a88177";
    $genreQuery = implode(',', $genreIds);
    $url = "https://api.themoviedb.org/3/discover/movie?api_key={$apiKey}&language=pt-BR&with_genres={$genreQuery}&region={$countryCode}";

    $response = file_get_contents($url);
    if ($response === FALSE) {
        die("Erro ao acessar a API.");
    }

    return json_decode($response, true)['results'];
}

function fetchComments($movieId, $conn)
{
    // Inclua o campo 'foto_usuario' na consulta SQL
    $sql = "SELECT c.id_comentario, c.texto AS comentario, u.id_usuario AS usuario_id, 
                   u.nome AS nome_usuario, u.foto, c.data_comentario AS data
            FROM comentarios c
            JOIN usuarios u ON c.fk_usuario = u.id_usuario
            WHERE c.fk_filme_api = $movieId
            ORDER BY c.data_comentario DESC";

    $result = mysqli_query($conn, $sql);
    $comments = [];

    // Adicione 'foto_usuario' ao array de resultados
    while ($row = mysqli_fetch_assoc($result)) {
        $comments[] = $row;
    }

    return $comments;
}


function fetchMovieCrew($movieId)
{
    $apiKey = "daf1bcaad0c418fca4f175ca58a88177";
    $url = "https://api.themoviedb.org/3/movie/{$movieId}/credits?api_key={$apiKey}&language=pt-BR";

    $response = file_get_contents($url);
    if ($response === FALSE) {
        die("Erro ao acessar a API.");
    }

    return json_decode($response, true)['crew'];
}

function isMovieSaved($userId, $movieId, $conn)
{
    $sql = "SELECT * FROM filmes_salvos WHERE id_usuario = ? AND id_filme_api = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $movieId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

$movieId = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$movieId) {
    die("ID do filme não fornecido.");
}

$movie = fetchMovieDetails($movieId);
$comments = fetchComments($movieId, $conn);
$cast = fetchMovieCast($movieId);

$productionCountries = $movie['production_countries'];
$countryCode = !empty($productionCountries) ? $productionCountries[0]['iso_3166_1'] : '';

$genreIds = array_column($movie['genres'], 'id');
$similarGenreMovies = fetchSimilarGenreMovies($genreIds, $countryCode);

$relatedMovies = array_filter($similarGenreMovies, function ($relatedMovie) use ($movieId) {
    return $relatedMovie['id'] != $movieId && !empty($relatedMovie['poster_path']);
});
$relatedMovies = array_unique($relatedMovies, SORT_REGULAR);

usort($relatedMovies, function ($a, $b) {
    return $b['popularity'] <=> $a['popularity'];
});

$isSaved = false;
if (isset($_SESSION['id_usuario'])) {
    $userId = $_SESSION['id_usuario'];
    $isSaved = isMovieSaved($userId, $movieId, $conn);
}
function isMovieFavorite($userId, $movieId, $conn)
{
    $sql = "SELECT * FROM filmes_favoritos WHERE fk_usuario = ? AND fk_filme_api = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $movieId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function isMovieWatched($userId, $movieId, $conn)
{
    $sql = "SELECT * FROM filmes_assistidos WHERE fk_usuario = ? AND fk_filme_api = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $movieId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function userHasRatedMovie($userId, $movieId, $conn)
{
    $sql = "SELECT * FROM avaliacao_filmes WHERE fk_usuario = ? AND fk_filme_api = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $movieId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

$hasRated = false;
if (isset($_SESSION['id_usuario'])) {
    $userId = $_SESSION['id_usuario'];
    $hasRated = userHasRatedMovie($userId, $movieId, $conn);
}

function getUserRating($userId, $movieId, $conn)
{
    $sql = "SELECT nota FROM avaliacao_filmes WHERE fk_usuario = ? AND fk_filme_api = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $movieId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['nota'];
    }
    return null;
}

function fetchAverageUserRating($movieId, $conn)
{
    $sql = "SELECT AVG(nota) AS media_avaliacao FROM avaliacao_filmes WHERE fk_filme_api = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $movieId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return round($row['media_avaliacao'], 2); // Arredonda para uma casa decimal
    }
    return null; // Caso não haja avaliações
}


include_once '../style/header.php';
?>

<style>
    .btn.salvar.checked i {
        color: blue;
    }

    .cast img {
        width: 100px;
        height: 150px;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .cast h6 {
        margin: 0;
    }

    .cast p {
        margin: 0;
        font-size: 0.9rem;
        color: grey;
    }

    .cast-item {
        text-align: center;
        margin-bottom: 20px;
    }

    .stars {
        display: flex;
        justify-content: center;
        font-size: 4rem;
    }

    .star {
        cursor: pointer;
        color: white;
    }

    .star.checked {
        color: orange;
    }

    .btn.salvar.checked i {
        color: blue;
    }

    .rating-input {
        display: none;
        animation: slideDown 0.3s forwards;
    }

    @keyframes slideDown {
        from {
            height: 0;
            opacity: 0;
        }

        to {
            height: auto;
            opacity: 1;
        }
    }
</style>

<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-5">
                <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="img-fluid rounded">
            </div>
            <div class="col-md-7">
                <?php
                $average = $movie['vote_average'];
                $formatted_average = strpos($average, '.') !== false ? substr($average, 0, strpos($average, '.') + 3) : $average;
                ?>
                <?php
                $averageUserRating = fetchAverageUserRating($movieId, $conn);
                ?>
                <h2><?= htmlspecialchars($movie['title']) ?>
                    <br>
                    <span style="color: grey">(<?= $formatted_average ?> TMDB)</span>
                    <?php if ($averageUserRating !== null): ?>
                        <span style="color: grey">(<?= $averageUserRating ?> MovieMatch)</span>
                    <?php endif; ?>
                </h2>
                <p><strong>Gêneros:</strong>
                    <?php foreach ($movie['genres'] as $genre) : ?>
                        <a href="filmes_genero.php?genre_id=<?= $genre['id'] ?>" class="badge badge-secondary"><?= htmlspecialchars($genre['name']) ?></a>
                    <?php endforeach; ?>
                </p>

                <p>
                    <strong>Sinopse:</strong> <?= htmlspecialchars($movie['overview']) ?>
                </p>

                <?php
                $crew = fetchMovieCrew($movieId);
                $directors = array_filter($crew, function ($member) {
                    return $member['job'] === 'Director';
                });
                ?>
                <strong>Diretor(es):</strong>
                <?php if (!empty($directors)) : ?>
                    <?php foreach ($directors as $director) : ?>
                        <a href="detalhes_diretor.php?id=<?= $director['id'] ?>" class="director-link"><?= htmlspecialchars($director['name']) ?></a><?php if (next($directors)) echo ', '; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <br>

                <strong>Data de Lançamento:</strong> <?= date('d M Y', strtotime($movie['release_date'])) ?>

                <br>

                <strong>Orçamento:</strong> <?= $movie['budget'] == 0 ? "Não informado" : "$" . number_format($movie['budget'], 2, ',', '.') ?>

                <br>

                <strong>Bilheteria:</strong> <?= $movie['revenue'] == 0 ? "Não informado" : "$" . number_format($movie['revenue'], 2, ',', '.') ?>

                <br>

                <strong>Tempo de Duração:</strong> <?= $movie['runtime'] == 0 ? "Não informado" : $movie['runtime'] . " min" ?>

                <br><br>

                <?php if (isset($_SESSION['id_usuario'])) : ?>
                    <div class="actions">
                        <?php if (!$hasRated) : ?>
                            <form action="../../Controller/adicionar_avaliacao.php" method="post" id="add-rating-form">
                                <input type="hidden" name="movie_id" value="<?= $movieId ?>">
                                <label class="btn avaliacao" for="rating-toggle">
                                    <i class="fas fa-star"></i> Adicionar nota
                                </label>
                                <input type="checkbox" class="checkbox" id="rating-toggle" style="display: none;">
                                <div id="rating-field" class="rating-input">
                                    <input type="number" name="rating" id="rating" min="0" max="10" step="0.1" style="width: 80px; margin-right: 10px">
                                    <button type="submit" class="btn">Salvar</button>
                                </div>
                            </form>
                        <?php else : ?>
                            <?php
                            $existingRating = getUserRating($userId, $movieId, $conn);
                            ?>

                            <form action="../../Controller/editar_avaliacao.php" method="post" id="edit-rating-form">
                                <input type="hidden" name="movie_id" value="<?= $movieId ?>">
                                <label class="btn avaliacao" for="edit-rating-toggle">
                                    <i class="fas fa-star" style="color: #a47b00"></i> Editar nota
                                </label>
                                <input type="checkbox" class="checkbox" id="edit-rating-toggle" style="display: none;">
                                <div id="edit-rating-field" class="rating-input">
                                    <input type="number" name="rating" id="edit_rating" min="0" max="10" step="0.1" style="width: 80px; margin-right: 10px" value="<?= htmlspecialchars($existingRating) ?>">
                                    <button type="submit" class="btn" style="width: 10px; margin: 0">Salvar</button>
                                </div>
                            </form>

                            <form action="../../Controller/remover_avaliacao.php" method="post" id="remove-rating-form">
                                <input type="hidden" name="movie_id" value="<?= $movieId ?>">
                                <button type="submit" class="btn btn-dark" style="height: 44px">
                                    <i class="fa fa-trash" style="margin: 0"></i>
                                </button>
                            </form>
                        <?php endif; ?>

                        <script>
                            const ratingToggle = document.getElementById('rating-toggle');
                            const ratingField = document.getElementById('rating-field');

                            ratingToggle.addEventListener('change', function() {
                                if (this.checked) {
                                    ratingField.style.display = 'block';
                                } else {
                                    ratingField.style.display = 'none';
                                }
                            });
                        </script>

                        <script>
                            const editRatingField = document.getElementById('edit-rating-field');
                            const editRatingToggle = document.getElementById('edit-rating-toggle');

                            editRatingToggle.addEventListener('change', function() {
                                if (this.checked) {
                                    editRatingField.style.display = 'block';
                                } else {
                                    editRatingField.style.display = 'none';
                                }
                            });
                        </script>

                        <?php
                        $isSaved = false;
                        if (isset($_SESSION['id_usuario'])) {
                            $userId = $_SESSION['id_usuario'];
                            $isSaved = isMovieSaved($userId, $movieId, $conn);
                            $isFavorite = isMovieFavorite($userId, $movieId, $conn);
                        }
                        ?>
                        <?php if ($isFavorite) : ?>
                            <form action="../../Controller/remover_favorito.php" method="post" id="remove-favorite-form">
                                <input type="hidden" name="id_usuario" value="<?= $_SESSION['id_usuario'] ?>">
                                <input type="hidden" name="id_filme_api" value="<?= $movieId ?>">
                                <label class="btn favorito checked">
                                    <input type="checkbox" class="checkbox" id="remove-favorite" name="remove_favorite" checked onclick="document.getElementById('remove-favorite-form').submit();">
                                    <i class="fas fa-heart"></i> Remover dos favoritos
                                </label>
                            </form>
                        <?php else : ?>
                            <form action="../../Controller/salvar_favorito.php" method="post" id="favorite-form">
                                <input type="hidden" name="id_usuario" value="<?= $_SESSION['id_usuario'] ?>">
                                <input type="hidden" name="id_filme_api" value="<?= $movieId ?>">
                                <label class="btn favorito">
                                    <input type="checkbox" class="checkbox" id="favorite" name="favorite" onclick="document.getElementById('favorite-form').submit();">
                                    <i class="fas fa-heart"></i> Favorito
                                </label>
                            </form>
                        <?php endif; ?>

                        <?php if ($isSaved) : ?>
                            <form action="../../Controller/remover_filme_salvo.php" method="post" id="remove-watch-later-form">
                                <input type="hidden" name="id_usuario" value="<?= $_SESSION['id_usuario'] ?>">
                                <input type="hidden" name="id_filme_api" value="<?= $movieId ?>">
                                <label class="btn salvar checked">
                                    <input type="checkbox" class="checkbox" id="remove-watch-later" name="remove_watch_later" checked onclick="document.getElementById('remove-watch-later-form').submit();">
                                    <i class="fas fa-bookmark"></i> Remover dos salvos
                                </label>
                            </form>
                        <?php else : ?>
                            <form action="../../Controller/salvar_filme.php" method="post" id="watch-later-form">
                                <input type="hidden" name="id_usuario" value="<?= $_SESSION['id_usuario'] ?>">
                                <input type="hidden" name="id_filme_api" value="<?= $movieId ?>">
                                <label class="btn salvar">
                                    <input type="checkbox" class="checkbox" id="watch-later" name="watch_later" onclick="document.getElementById('watch-later-form').submit();">
                                    <i class="fas fa-bookmark"></i> Assistir mais tarde
                                </label>
                            </form>
                        <?php endif; ?>

                        <?php
                        $isWatched = false;
                        if (isset($_SESSION['id_usuario'])) {
                            $userId = $_SESSION['id_usuario'];
                            $isWatched = isMovieWatched($userId, $movieId, $conn);
                        }
                        ?>

                        <?php if ($isWatched) : ?>
                            <form action="../../Controller/remover_assistido.php" method="post" id="remove-watched-form">
                                <input type="hidden" name="id_usuario" value="<?= $_SESSION['id_usuario'] ?>">
                                <input type="hidden" name="id_filme_api" value="<?= $movieId ?>">
                                <label class="btn assistido checked">
                                    <input type="checkbox" class="checkbox" id="remove-watched" name="remove_watched" checked onclick="document.getElementById('remove-watched-form').submit();">
                                    <i class="fas fa-eye-slash"></i>
                                </label>
                            </form>
                        <?php else : ?>
                            <form action="../../Controller/salvar_assistido.php" method="post" id="watched-form">
                                <input type="hidden" name="id_usuario" value="<?= $_SESSION['id_usuario'] ?>">
                                <input type="hidden" name="id_filme_api" value="<?= $movieId ?>">
                                <label class="btn assistido">
                                    <input type="checkbox" class="checkbox" id="watched" name="watched" onclick="document.getElementById('watched-form').submit();">
                                    <i class="fas fa-eye"></i>
                                </label>
                            </form>
                        <?php endif; ?>
                    </div>

                    <form class="mt-4" action="../../Controller/comentar_filme.php" method="post">
                        <input type="hidden" name="movie_id" value="<?= $movieId ?>">
                        <div class="form-group">
                            <label for="comentario">Comentário:</label>
                            <textarea name="comentario" id="comentario" class="form-control" rows="3" placeholder="Escreva seu comentário"></textarea>
                        </div>
                        <button type="submit" class="btn btn-dark" style="width: 633px">Enviar Comentário</button>
                    </form>
                <?php else : ?>
                    <hr style="background-color: orange;">
                    <div >
                        Sentindo falta de algo? <a href="login.php" style="color:orange">faça login</a> para interagir com o filme.
                    </div>
                <?php endif; ?>
            </div>

            <hr>

            <div class="cast-section mt-5">
                <h3>
                    <span>Elenco</span>
                </h3>
                <div class="row" id="cast-container">
                    <?php foreach ($cast as $index => $castMember) : ?>
                        <?php if (!empty($castMember['profile_path'])) : ?>
                            <div class="col-md-2 cast-item <?= $index >= 6 ? 'd-none' : '' ?>">
                                <a href="detalhes_ator.php?id=<?= $castMember['id'] ?>">
                                    <img src="https://image.tmdb.org/t/p/w200<?= $castMember['profile_path'] ?>" alt="<?= htmlspecialchars($castMember['name']) ?>">
                                    <h6><?= htmlspecialchars($castMember['name']) ?></h6>
                                    <p><?= htmlspecialchars($castMember['character']) ?></p>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if (count($cast) > 6) : ?>
                        <a id="toggle-link" href="javascript:void(0)" class="ver-mais-link" style="margin-left: 20px">ver mais</a>
                    <?php endif; ?>
                </div>
            </div>

            <script>
                document.getElementById('toggle-link').addEventListener('click', function() {
                    var castItems = document.querySelectorAll('#cast-container .cast-item');
                    var hiddenItems = document.querySelectorAll('#cast-container .cast-item.d-none');

                    if (hiddenItems.length > 0) {
                        castItems.forEach(function(item) {
                            item.classList.remove('d-none');
                        });
                        this.textContent = 'ver menos';
                    } else {
                        castItems.forEach(function(item, index) {
                            if (index >= 6) {
                                item.classList.add('d-none');
                            }
                        });
                        this.textContent = 'ver mais';
                    }
                });
            </script>

            <?php
            function fetchMovieBackdrops($movieId)
            {
                $apiKey = "daf1bcaad0c418fca4f175ca58a88177";
                $url = "https://api.themoviedb.org/3/movie/{$movieId}/images?api_key={$apiKey}";

                $response = file_get_contents($url);
                if ($response === FALSE) {
                    die("Erro ao acessar a API.");
                }

                return json_decode($response, true)['backdrops'];
            }

            $backdrops = fetchMovieBackdrops($movieId);
            ?>

            <style>
                .backdrop-section img {
                    max-height: 300px;
                    object-fit: cover;
                }

                .carousel-item .col-4 {
                    padding: 0 5px;
                }
            </style>
            <div class="backdrop-section mt-5">
                <h3>Imagens Relacionadas</h3>
                <?php if (count($backdrops) > 0): ?>
                    <div id="backdropCarousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $chunked_backdrops = array_chunk($backdrops, 3);
                            foreach ($chunked_backdrops as $index => $backdropChunk): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <div class="row">
                                        <?php foreach ($backdropChunk as $backdrop): ?>
                                            <div class="col-4">
                                                <img src="https://image.tmdb.org/t/p/w500<?= $backdrop['file_path'] ?>" class="d-block w-100" alt="Backdrop Image">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <a class="carousel-control-prev" href="#backdropCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#backdropCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                <?php else: ?>
                    <p>Nenhuma imagem relacionada encontrada.</p>
                <?php endif; ?>
            </div>

            <div class="comment-section">
                <h3>Comentários</h3>
                <?php if (count($comments) > 0) : ?>
                    <?php foreach ($comments as $comment) : ?>
                        <div class="comment">
                            <div class="comment-meta d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <a href="perfil_usuario.php?id_usuario=<?= $comment['usuario_id'] ?>">
                                        <img src="../uploads/<?= htmlspecialchars($comment['foto'] ?? 'user.png') ?>" alt="Foto do Usuário" class="profile-image" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;">
                                        <span>
                                            <strong style="color: orange">
                                                <?= htmlspecialchars($comment['nome_usuario']) ?>
                                    </a>
                                    </strong>
                                    </span>
                                    -
                                    <span style="margin-left: 10px;"><?= date('d M Y', strtotime($comment['data'])) ?></span>
                                </div>
                                <?php
                                if (isset($_SESSION['id_usuario']) && ($comment['usuario_id'] == $_SESSION['id_usuario'] || $_SESSION['admin'] == 1)) : ?>
                                    <div class="ml-auto">
                                        <a href="../../Controller/excluir_comentario.php?id=<?= $comment['id_comentario'] ?>&movieId=<?= $movieId ?>" class="fas fa-trash" title="Excluir comentário" style="color: grey;"></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <hr style="background-color: grey;">
                            <div class="comment-text">
                                <p><?= htmlspecialchars($comment['comentario']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Nenhum comentário ainda. Seja o primeiro a comentar!</p>
                <?php endif; ?>
            </div>

            <br>

            <div class="related-movies">
                <h3>Filmes Relacionados</h3>
                <div class="row">
                    <?php foreach ($relatedMovies as $relatedMovie) : ?>
                        <div class="col-md-3">
                            <div class="movie">
                                <a href="detalhes_filmes.php?id=<?= $relatedMovie['id'] ?>">
                                    <img src="https://image.tmdb.org/t/p/w300<?= $relatedMovie['poster_path'] ?>" alt="<?= htmlspecialchars($relatedMovie['title']) ?>">
                                    <h6><?= htmlspecialchars($relatedMovie['title']) ?></h6>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>