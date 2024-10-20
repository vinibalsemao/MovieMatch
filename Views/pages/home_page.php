<?php
session_start();
include '../../Model/api.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$movies = fetchMovies($page);

function fetchMovieTrailer($movieId)
{
    $apiKey = "daf1bcaad0c418fca4f175ca58a88177";
    $url = "https://api.themoviedb.org/3/movie/{$movieId}/videos?api_key={$apiKey}&language=pt-BR";

    $response = file_get_contents($url);
    if ($response === FALSE) {
        die("Erro ao acessar a API.");
    }

    $videos = json_decode($response, true)['results'];
    foreach ($videos as $video) {
        if ($video['type'] == 'Trailer' && $video['site'] == 'YouTube') {
            return $video['key'];
        }
    }

    return null;
}

include_once '../style/header.php';
?>
<style>
    <?php
    include_once '../style/input_style.css';
    ?>#featuredTrailers {
        scrollbar-color: black black;
    }

    #featuredTrailers::-webkit-scrollbar {
        height: 8px;
    }

    #featuredTrailers::-webkit-scrollbar-thumb {
        background-color: white;
        border-radius: 10px;
    }

    #featuredTrailers::-webkit-scrollbar-track {
        background: black;
    }
</style>

<body>
    <main class="container">
        <div class="py-5 text-center">
            <h1>Bem-vindo ao <b style="color: orange">MovieMatch</b></h1>
            <p class="font-italic">Encontre filmes incríveis e conecte-se com pessoas que compartilham seu gosto!</p>
        </div>

        <hr style="background-color: orange">

        <section class="sessao-filmes mt-5">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="ml-2">Filmes em <b style="color: orange">Destaque</b></h4>
                <a href="filmes_populares.php" id="link">Ver todos</a>
            </div>
            <div id="featuredMovies" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $active = true;
                    foreach ($movies['results'] as $index => $movie) :
                        if ($index % 4 == 0) {
                            echo ($active) ? '<div class="carousel-item active">' : '<div class="carousel-item">';
                            $active = false;
                            echo '<div class="row">';
                        }
                    ?>
                        <div class="col-md-3">
                            <div class="card mb-3">
                                <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" class="card-img-top" alt="<?= htmlspecialchars($movie['title']) ?>">
                            </div>
                        </div>
                    <?php
                        if (($index + 1) % 4 == 0 || ($index + 1) == count($movies['results'])) {
                            echo '</div></div>';
                        }
                    endforeach;
                    ?>
                </div>
                <a class="carousel-control-prev" href="#featuredMovies" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#featuredMovies" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </section>

        <hr style="background-color: orange">

        <?php if (isset($_SESSION['id_usuario'])) : ?>
            <div class="container">
                <div class="row input-container">
                    <div class="style-form-input full text-center">
                        <input type="submit" class="btn-submit" value="Encontre pessoas com gostos em comum!" name="submit" onclick="showLoading()" />
                    </div>
                </div>
            </div>
            <div id="loadingScreen" style="display: none;">
                <div class="loading-content">
                    <h3>Buscando usuários com gostos semelhantes...</h3>
                    <div class="spinner"></div>
                </div>
            </div>

        <?php endif; ?>

        <style>
            #loadingScreen {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.8);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
            }

            /* Centralizando o conteúdo */
            .loading-content {
                text-align: center;
                color: white;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            /* Spinner de carregamento */
            .spinner {
                border: 8px solid rgba(255, 255, 255, 0.2);
                border-top: 8px solid orange;
                border-radius: 50%;
                width: 100px;
                height: 100px;
                animation: spin 1s linear infinite;
            }

            /* Animação do spinner */
            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }
        </style>

        <script>
            function showLoading() {
                document.getElementById('loadingScreen').style.display = 'flex';

                setTimeout(function() {
                    window.location.href = "match.php";
                }, 3000);
            }
        </script>

        <div class="my-5">
            <h4>No <b style="color: orange">MovieMatch</b> você poderá...</h4>
            <div class="row mt-3">
                <div class="col-md-4 mb-3">
                    <div class="card feature-card text-center">
                        <div class="card-body">
                            <i class="fas fa-eye fa-2x mb-3"></i><br>
                            <a href="perfil.php" class="card-title" style="color: white; text-decoration: none; font-family: 'Montserrat', 'Times New Roman', Times, serif;">Acompanhar todos os filmes que você já assistiu e suas críticas</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card feature-card text-center">
                        <div class="card-body">
                            <i class="fas fa-heart fa-2x mb-3"></i><br>
                            <a href="#" class="card-title" style="color: white; text-decoration: none; font-family: 'Montserrat', 'Times New Roman', Times, serif;">Mostrar um pouco de amor pelos seus filmes favoritos com seu like</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card feature-card text-center">
                        <div class="card-body">
                            <i class="fas fa-pencil-alt fa-2x mb-3"></i><br>
                            <a href="#" class="card-title" style="color: white; text-decoration: none; font-family: 'Montserrat', 'Times New Roman', Times, serif;">Escrever e compartilhar seus comentários no nosso fórum de filmes</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card feature-card text-center">
                        <div class="card-body">
                            <i class="fas fa-star fa-2x mb-3"></i><br>
                            <a href="filmes_populares.php" class="card-title" style="color: white; text-decoration: none; font-family: 'Montserrat', 'Times New Roman', Times, serif;">Avaliar filmes e ver o que seus amigos acharam deles</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card feature-card text-center">
                        <div class="card-body">
                            <i class="fas fa-calendar-alt fa-2x mb-3"></i><br>
                            <a href="#" class="card-title" style="color: white; text-decoration: none; font-family: 'Montserrat', 'Times New Roman', Times, serif;">Conversar com pessoas com os mesmos gostos que você com a nossa ferramenta de match</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card feature-card text-center">
                        <div class="card-body">
                            <i class="fas fa-th-large fa-2x mb-3"></i><br>
                            <a href="#" class="card-title" style="color: white; text-decoration: none; font-family: 'Montserrat', 'Times New Roman', Times, serif;">Criar listas de filmes para compartilhar com seus amigos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-white text-center py-4 footer">
        <p>&copy; 2024 <a href="#" style="color: orange">MovieMatch</a>. Todos os direitos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>