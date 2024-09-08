<?php
session_start();
include '../../Model/api.php';
include '../../Controller/pagination.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$movies = fetchMovies($page);

include_once '../style/header.php';
?>

<style>
    .movie p {
        margin: 0;
        color: grey;
    }
</style>

<body>
    <main class="container">
        <div class="py-5 text-center">
            <h1>Filmes <b style="color: orange">Populares</b></h1>
            <p class="font-italic">Veja e explore os filmes que estão bombando no momento!</p>
        </div>

        <div class="movies">
            <?php foreach ($movies['results'] as $movie) : ?>
                <div class="movie">
                    <a href="detalhes_filmes.php?id=<?= $movie['id'] ?>">
                        <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                        <div class="movie-details">
                            <h6><?= htmlspecialchars($movie['title']) ?></h6>
                            <p><?= htmlspecialchars($movie['release_date']) ? date('Y', strtotime($movie['release_date'])) : 'Data não informada' ?></p>
                    </a>
                </div>
        </div>
    <?php endforeach; ?>
    <div class="pagination">
        <?php renderPagination($page, $movies['total_pages']); ?>
    </div>
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