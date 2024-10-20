<?php
session_start();
include_once '../../Model/conecta_bd.php';
include_once '../../Model/api.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['id_usuario'];
$apiKey = "daf1bcaad0c418fca4f175ca58a88177";
$apiUrl = "https://api.themoviedb.org/3/movie/";

// Consulta para pegar filmes assistidos
$sqlAssistidos = "SELECT fk_filme_api FROM filmes_assistidos WHERE fk_usuario = $userId";
$resultAssistidos = mysqli_query($conn, $sqlAssistidos);
$assistidos = [];
while ($row = mysqli_fetch_assoc($resultAssistidos)) {
    $assistidos[] = $row['fk_filme_api'];
}

// Consulta para pegar filmes favoritos
$sqlFavoritos = "SELECT fk_filme_api FROM filmes_favoritos WHERE fk_usuario = $userId";
$resultFavoritos = mysqli_query($conn, $sqlFavoritos);
$favoritos = [];
while ($row = mysqli_fetch_assoc($resultFavoritos)) {
    $favoritos[] = $row['fk_filme_api'];
}

// Consulta para pegar filmes para assistir mais tarde
$sqlAssistirMaisTarde = "SELECT id_filme_api FROM filmes_salvos WHERE id_usuario = $userId";
$resultAssistirMaisTarde = mysqli_query($conn, $sqlAssistirMaisTarde);
$assistirMaisTarde = [];
while ($row = mysqli_fetch_assoc($resultAssistirMaisTarde)) {
    $assistirMaisTarde[] = $row['id_filme_api'];
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

    .section-title {
        margin-top: 40px;
        text-align: center;
        font-size: 24px;
        font-weight: bold;
    }
</style>

<body>
    <div class="container my-5">
        <div class="py-5 text-center">
            <h1>Filmes <b style="color: orange">Salvos</b></h1>
            <p class="font-italic">Aqui você encontra todos os filmes que salvou.</p>
        </div>

        <!-- Seção: Filmes Assistidos -->
        <div class="section-title">Filmes Assistidos</div>
        <div class="row">
            <?php if (count($assistidos) > 0) : ?>
                <?php
                foreach ($assistidos as $movieId) {
                    $url = "{$apiUrl}{$movieId}?api_key={$apiKey}&language=pt-BR";
                    $response = file_get_contents($url);
                    if ($response === FALSE) continue;
                    $movie = json_decode($response, true);
                    if (isset($movie['id'])) :
                ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="movie">
                                <a href="detalhes_filmes.php?id=<?= $movie['id'] ?>">
                                    <img src="https://image.tmdb.org/t/p/w300<?= $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                                    <div class="movie-info">
                                        <h6 class="movie-title"><?= htmlspecialchars($movie['title']) ?></h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php } ?>
            <?php else : ?>
                <p class="text-center">Você ainda não assistiu nenhum filme...</p>
            <?php endif; ?>
        </div>

        <!-- Seção: Filmes Favoritos -->
        <div class="section-title">Filmes Favoritos</div>
        <div class="row">
            <?php if (count($favoritos) > 0) : ?>
                <?php
                foreach ($favoritos as $movieId) {
                    $url = "{$apiUrl}{$movieId}?api_key={$apiKey}&language=pt-BR";
                    $response = file_get_contents($url);
                    if ($response === FALSE) continue;
                    $movie = json_decode($response, true);
                    if (isset($movie['id'])) :
                ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="movie">
                                <a href="detalhes_filmes.php?id=<?= $movie['id'] ?>">
                                    <img src="https://image.tmdb.org/t/p/w300<?= $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                                    <div class="movie-info">
                                        <h6 class="movie-title"><?= htmlspecialchars($movie['title']) ?></h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php } ?>
            <?php else : ?>
                <p class="text-center">Você ainda não tem filmes favoritos...</p>
            <?php endif; ?>
        </div>

        <!-- Seção: Filmes para Assistir Mais Tarde -->
        <div class="section-title">Filmes para Assistir Mais Tarde</div>
        <div class="row">
            <?php if (count($assistirMaisTarde) > 0) : ?>
                <?php
                foreach ($assistirMaisTarde as $movieId) {
                    $url = "{$apiUrl}{$movieId}?api_key={$apiKey}&language=pt-BR";
                    $response = file_get_contents($url);
                    if ($response === FALSE) continue;
                    $movie = json_decode($response, true);
                    if (isset($movie['id'])) :
                ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="movie">
                                <a href="detalhes_filmes.php?id=<?= $movie['id'] ?>">
                                    <img src="https://image.tmdb.org/t/p/w300<?= $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                                    <div class="movie-info">
                                        <h6 class="movie-title"><?= htmlspecialchars($movie['title']) ?></h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php } ?>
            <?php else : ?>
                <p class="text-center">Você ainda não salvou nenhum filme para assistir mais tarde...</p>
            <?php endif; ?>
        </div>
    </div>

    <footer class="text-white text-center py-4 footer">
        <p>&copy; 2024 <a href="#" style="color: orange">MovieMatch</a>. Todos os direitos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>