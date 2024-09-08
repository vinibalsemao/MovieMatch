<?php
session_start();
include '../../Model/api.php';

if (isset($_POST['query']) && !empty($_POST['query'])) {
    $query = urlencode($_POST['query']);
    $api_key = 'daf1bcaad0c418fca4f175ca58a88177';

    $url = "https://api.themoviedb.org/3/search/movie?api_key={$api_key}&language=pt-BR&query={$query}";

    $response = file_get_contents($url);
    $movies = json_decode($response, true);
} else {
    $movies = [];
}

include_once '../style/header.php';
?>

<body>
    <main class="container">
        <div class="py-5 text-center">
            <h1>Resultados da <b style="color: orange"> busca</b></h1>
            <p class="font-italic">Veja e explore os filmes encontrados!</p>
        </div>
        <div class="movies">
            <?php if (!empty($movies['results'])) : ?>
                <?php foreach ($movies['results'] as $movie) : ?>
                    <div class="movie">
                        <a target="_blank" href="detalhes_filmes.php?id=<?= $movie['id'] ?>">
                            <img src="<?php
                                        if (empty($movie['poster_path'])) {
                                            echo '../uploads/poster.png';
                                        } else {
                                            echo 'https://image.tmdb.org/t/p/w300' . $movie['poster_path'];
                                        }  ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                            <div class="movie-details">
                                <h6><?= htmlspecialchars($movie['title']) ?></h6>

                        </a>
                    </div>
        </div>
    <?php endforeach; ?>
<?php else :
                echo "<h3>Nenhum filme encontrado.</h3> <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
            endif; ?>
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