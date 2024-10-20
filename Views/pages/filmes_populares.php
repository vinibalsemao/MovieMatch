<?php
session_start();
include '../../Model/api.php';
include '../../Controller/pagination.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$query = isset($_POST['query']) ? $_POST['query'] : ''; // Pega a pesquisa, se houver

// Função para buscar filmes
function searchMovies($query, $page = 1)
{
    $api_key = 'daf1bcaad0c418fca4f175ca58a88177';
    $url = "https://api.themoviedb.org/3/search/movie?api_key={$api_key}&language=pt-BR&query=" . urlencode($query) . "&page={$page}";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

// Função para filmes populares já existente
// function fetchMovies($page = 1)
// {
//     $api_key = 'daf1bcaad0c418fca4f175ca58a88177';
//     $url = "https://api.themoviedb.org/3/movie/popular?api_key={$api_key}&language=pt-BR&page={$page}";
//     $response = file_get_contents($url);
//     return json_decode($response, true);
// }

// Verifica se há uma busca por filmes, senão exibe os populares
if (!empty($query)) {
    $movies = searchMovies($query, $page); // Função que busca filmes pela query
} else {
    $movies = fetchMovies($page); // Filmes populares
}

include_once '../style/header.php';
?>

<style>
    .movie p {
        margin: 0;
        color: grey;
    }

    .pagination {
        margin-top: 20px;
    }
</style>

<body>
    <main class="container">
        <div class="py-5 text-center">
            <h1>Filmes <b style="color: orange">Populares</b></h1>
            <p class="font-italic">Veja e explore os filmes que estão bombando no momento!</p>
        </div>

        <!-- Barra de busca de filmes -->
        <form method="POST" class="mb-4">
            <div class="input-group">
                <input type="text" name="query" class="form-control" placeholder="Busque por filmes" value="<?= htmlspecialchars($query) ?>">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </div>
            </div>
        </form>

        <div class="movies">
            <?php if (!empty($movies['results'])) : ?>
                <?php foreach ($movies['results'] as $movie) : ?>
                    <div class="movie">
                        <a href="detalhes_filmes.php?id=<?= $movie['id'] ?>">
                            <img src="<?php
                                        if (empty($movie['poster_path'])) {
                                            echo '../uploads/poster.png';
                                        } else {
                                            echo 'https://image.tmdb.org/t/p/w300' . $movie['poster_path'];
                                        }  ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                            <div class="movie-details">
                                <h6><?= htmlspecialchars($movie['title']) ?></h6>
                                <p><?= htmlspecialchars($movie['release_date']) ? date('Y', strtotime($movie['release_date'])) : 'Data não informada' ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
                <div class="pagination">
                    <?php renderPagination($page, $movies['total_pages']); ?>
                </div>
            <?php else : ?>
                <p class="text-center">Nenhum filme encontrado.</p>
            <?php endif; ?>
        </div>
    </main>

    <br>


    <footer class="text-white text-center py-4 footer">
        <p>&copy; 2024 <a href="#" style="color: orange">MovieMatch</a>. Todos os direitos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>