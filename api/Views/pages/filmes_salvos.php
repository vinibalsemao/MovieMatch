<?php
session_start();
include_once '../../Model/conecta_bd.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['id_usuario'];

$sql = "SELECT id_filme_api FROM filmes_salvos WHERE id_usuario = $userId";
$result = mysqli_query($conn, $sql);
$savedMovieIds = [];
while ($row = mysqli_fetch_assoc($result)) {
    $savedMovieIds[] = $row['id_filme_api'];
}

include_once '../style/header.php';
?>

<style>
    .movie {
        text-align: center;
        margin-bottom: 20px;
    }

    .movie img {
        width: 100%;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .movie-info {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
    }

    .movie form {
        display: inline-block;
    }

    .movie form button {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        color: red;
        padding-bottom: 15px;
    }

    .movie form button:hover {
        color: darkred;
    }

    .p {
        text-align: center;
        justify-content: center;
    }
</style>

<body>
    <div class="container my-5">
        <div class="py-5 text-center">
            <h1>Filmes <b style="color: orange">Salvos</b></h1>
            <p class="font-italic">Aqui você encontra todos os filmes que salvou para assistir mais tarde.</p>
        </div>
        <br><br>
        <div class="row">
            <?php if (count($savedMovieIds) > 0) : ?>
                <?php
                $apiKey = "daf1bcaad0c418fca4f175ca58a88177";
                $apiUrl = "https://api.themoviedb.org/3/movie/";

                foreach ($savedMovieIds as $movieId) {
                    $url = "{$apiUrl}{$movieId}?api_key={$apiKey}&language=pt-BR";

                    $response = file_get_contents($url);
                    if ($response === FALSE) {
                        continue;
                    }

                    $movie = json_decode($response, true);

                    if (isset($movie['id'])) : ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="movie">
                                <a href="detalhes_filmes.php?id=<?= $movie['id'] ?>">
                                    <img src="https://image.tmdb.org/t/p/w300<?= $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>"><br><br>
                                    <div class="movie-info">
                                        <h6 class="movie-title"><?= htmlspecialchars($movie['title']) ?></h6>
                                        <form method="POST" action="../../Controller/remover_filme.php">
                                            <input type="hidden" name="id_usuario" value="<?= $_SESSION['id_usuario'] ?>">
                                            <input type="hidden" name="id_filme_api" value="<?= $movie['id'] ?>">
                                            <button type="submit" class="fa fa-trash"></button>
                                        </form>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php } ?>
            <?php else : ?>
                <p>Você ainda não salvou nenhum filme...</p>
                <br><br>
                <br><br>
                <br><br>
                <br><br>
                <br><br>
                <br><br>
                <br><br>
                <br><br>
                <br><br>
                <br><br><br>
            <?php endif; ?>
        </div>
    </div>

    <br><br><br><br><br><br>

    <footer class="text-white text-center py-4 footer">
        <p>&copy; 2024 <a href="#" style="color: orange">MovieMatch</a>. Todos os direitos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>