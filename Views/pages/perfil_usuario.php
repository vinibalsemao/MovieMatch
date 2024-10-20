<?php
session_start();
include_once '../../Model/conecta_bd.php';

if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['erro'] = "Você precisa estar logado para acessar essa página!";
    header("Location: home_page.php?error=alreadyLoggedIn");
    exit();
}
include_once '../style/header.php';

$usuario_logado_id = intval($_SESSION['id_usuario']);

if (isset($_GET['id_usuario'])) {
    $id_usuario = intval($_GET['id_usuario']);

    $query = "SELECT * FROM usuarios WHERE id_usuario = $id_usuario";
    $result = mysqli_query($conn, $query);
    $usuario = mysqli_fetch_assoc($result);

    if ($usuario) {
        $nome = htmlspecialchars($usuario['nome']);
        $email = htmlspecialchars($usuario['email']);
        $descricao = htmlspecialchars(($usuario['descricao']));
        $foto = !empty($usuario['foto']) ? htmlspecialchars($usuario['foto']) : 'user.png';
    } else {
        echo "Usuário não encontrado.";
        exit();
    }
} else {
    echo "ID de usuário inválido.";
    exit();
}

$sqlFavoritos = "SELECT fk_filme_api FROM filmes_favoritos WHERE fk_usuario = $id_usuario";
$resultFavoritos = mysqli_query($conn, $sqlFavoritos);
$favoritos = [];

while ($row = mysqli_fetch_assoc($resultFavoritos)) {
    $favoritos[] = $row['fk_filme_api'];
}

$sqlAssistirMaisTarde = "SELECT id_filme_api FROM filmes_salvos WHERE id_usuario = $id_usuario";
$resultAssistirMaisTarde = mysqli_query($conn, $sqlAssistirMaisTarde);
$assistirMaisTarde = [];

while ($row = mysqli_fetch_assoc($resultAssistirMaisTarde)) {
    $assistirMaisTarde[] = $row['id_filme_api'];
}

$is_self = ($usuario_logado_id == $id_usuario);

$ja_sao_amigos = false;

$query_amizade = "SELECT * FROM amigos WHERE 
                  (fk_usuario1 = $usuario_logado_id AND fk_usuario2 = $id_usuario) 
               OR (fk_usuario1 = $id_usuario AND fk_usuario2 = $usuario_logado_id)";
$result_amizade = mysqli_query($conn, $query_amizade);

if (mysqli_num_rows($result_amizade) > 0) {
    $ja_sao_amigos = true;
}

?>


<style>
    .movie {
        margin: 10px;
    }

    .movie:hover {
        transform: scale(1.0);
    }

    .footer {
        margin-top: 370px;
    }

    .user-image {
        width: 250px;
        height: 250px;
        object-fit: cover;
        border: none;
    }

    .hidden-movie {
        display: none;
    }

    .hidden-favoritos {
        display: none;
    }

    .hidden-assistir {
        display: none;
    }
</style>

<body>
    <div class="container">
        <div class="py-5">
            <div class="py-5 text-center">
                <h1>Perfil de <b style="color: orange"><?= $nome ?></b></h1>
                <p class="font-italic">Aqui estão as informações e os filmes que <?= $nome ?> mais gostou de assistir.</p>
            </div>

            <hr style="background-color: orange">

            <br>

            <div class="row">
                <div class="col-md-4">
                    <img src="../uploads/<?= $foto ?>" class="user-image" alt="Foto do Usuário">
                    <?php if (!$is_self): ?>
                        <form method="POST" action="../../Controller/adicionar_amigo.php">
                            <input type="hidden" name="id_amigo" value="<?= $id_usuario ?>">

                            <?php if (!$ja_sao_amigos): ?>
                                <button type="submit" class="btn btn-dark" style="width: 250px">
                                    <i class="fa fa-user small" style="margin-right: 2px"></i> Seguir
                                </button>
                            <?php else: ?>
                                <button type="submit" name="deixar_de_seguir" class="btn btn-dark" style="width: 250px">
                                    <i class="fa fa-user-times small" style="margin-right: 2px"></i> Deixar de seguir
                                </button>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>

                    <br>

                    <?php
                    $querySeguidores = "SELECT COUNT(*) AS total_seguidores FROM amigos WHERE fk_usuario2 = $id_usuario";
                    $resultSeguidores = mysqli_query($conn, $querySeguidores);
                    $seguidoresData = mysqli_fetch_assoc($resultSeguidores);
                    $totalSeguidores = $seguidoresData['total_seguidores'];
                    ?>
                    <p>
                        <b>Nome:</b> <?= $nome ?> <br>
                        <b>Email:</b> <?= $email ?> <br>
                        <b>Seguidores:</b> <a href="#" data-toggle="modal" data-target="#modalSeguidores"><?= $totalSeguidores ?></a>
                    </p>

                </div>

                <div class="col-md-5 descricao-container">
                    <br>
                    <h3 style="color: orange">Descrição:</h3>
                    <div class="descricao-texto">
                        <?php
                        if ($descricao == "") {
                            echo "<p>O usuário ainda não possui uma descrição.</p>";
                        } else {
                            echo "<p>" . $descricao . "</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>


            <hr style="background-color: orange">

            <div class="container my-5">
                <h1>Filmes favoritos</h1>
                <div class="row">
                    <?php if (count($favoritos) > 0) : ?>
                        <?php
                        $apiKey = "daf1bcaad0c418fca4f175ca58a88177";
                        $apiUrl = "https://api.themoviedb.org/3/movie/";

                        $count = 0;
                        foreach ($favoritos as $movieId) {
                            $url = "{$apiUrl}{$movieId}?api_key={$apiKey}&language=pt-BR";
                            $response = file_get_contents($url);
                            if ($response === FALSE) continue;

                            $movie = json_decode($response, true);
                            if (isset($movie['id'])) :
                                $hiddenClass = ($count >= 4) ? 'hidden-favoritos' : '';
                        ?>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 movie <?= $hiddenClass ?>">
                                    <a href="detalhes_filmes.php?id=<?= $movie['id'] ?>">
                                        <img src="https://image.tmdb.org/t/p/w300<?= $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                                    </a>
                                </div>
                        <?php
                                $count++;
                            endif;
                        }
                        ?>

                        <?php if (count($favoritos) > 4) : ?>
                            <div class="col-12 text-center">
                                <button id="ver-mais-favoritos-btn" class="btn btn-dark" style="width: 100%">Ver mais</button>
                            </div>
                        <?php endif; ?>

                    <?php else : ?>
                        <p style="margin-left: 15px">Você ainda não adicionou nenhum filme favorito...</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="container my-5">
                <h1>Assistir Mais Tarde</h1>
                <div class="row">
                    <?php if (count($assistirMaisTarde) > 0) : ?>
                        <?php
                        $count = 0;
                        foreach ($assistirMaisTarde as $movieId) {
                            $url = "{$apiUrl}{$movieId}?api_key={$apiKey}&language=pt-BR";
                            $response = file_get_contents($url);
                            if ($response === FALSE) continue;

                            $movie = json_decode($response, true);
                            if (isset($movie['id'])) :
                                $hiddenClass = ($count >= 4) ? 'hidden-assistir' : '';
                        ?>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 movie <?= $hiddenClass ?>">
                                    <a href="detalhes_filmes.php?id=<?= $movie['id'] ?>">
                                        <img src="https://image.tmdb.org/t/p/w300<?= $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                                    </a>
                                </div>
                        <?php
                                $count++;
                            endif;
                        }
                        ?>

                        <?php if (count($assistirMaisTarde) > 4) : ?>
                            <div class="col-12 text-center">
                                <button id="ver-mais-assistir-btn" class="btn btn-dark" style="width: 100%">Ver mais</button>
                            </div>
                        <?php endif; ?>

                    <?php else : ?>
                        <p style="margin-left: 15px">Você ainda não adicionou nenhum filme para assistir mais tarde...</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <footer class="text-white text-center py-4 footer">
        <p>&copy; 2024 <a href="#" style="color: orange">MovieMatch</a>. Todos os direitos reservados.</p>
    </footer>

    <?php
    include_once('../modals/modal_seguidores.php');
    ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Botão para Favoritos
            const verMaisFavoritosBtn = document.getElementById('ver-mais-favoritos-btn');
            const hiddenFavoritos = document.querySelectorAll('.hidden-favoritos');
            let allFavoritosVisible = false;

            if (verMaisFavoritosBtn) {
                verMaisFavoritosBtn.addEventListener('click', function() {
                    if (!allFavoritosVisible) {
                        hiddenFavoritos.forEach(function(movie) {
                            movie.style.display = 'block'; // Exibe os filmes ocultos
                        });
                        verMaisFavoritosBtn.textContent = 'Mostrar menos'; // Altera o texto do botão para "Mostrar menos"
                        allFavoritosVisible = true;
                    } else {
                        hiddenFavoritos.forEach(function(movie) {
                            movie.style.display = 'none'; // Oculta novamente os filmes extras
                        });
                        verMaisFavoritosBtn.textContent = 'Ver mais'; // Altera o texto do botão para "Ver mais"
                        allFavoritosVisible = false;
                    }
                });
            }

            // Botão para Assistir Mais Tarde
            const verMaisAssistirBtn = document.getElementById('ver-mais-assistir-btn');
            const hiddenAssistir = document.querySelectorAll('.hidden-assistir');
            let allAssistirVisible = false;

            if (verMaisAssistirBtn) {
                verMaisAssistirBtn.addEventListener('click', function() {
                    if (!allAssistirVisible) {
                        hiddenAssistir.forEach(function(movie) {
                            movie.style.display = 'block'; // Exibe os filmes ocultos
                        });
                        verMaisAssistirBtn.textContent = 'Mostrar menos'; // Altera o texto do botão para "Mostrar menos"
                        allAssistirVisible = true;
                    } else {
                        hiddenAssistir.forEach(function(movie) {
                            movie.style.display = 'none'; // Oculta novamente os filmes extras
                        });
                        verMaisAssistirBtn.textContent = 'Ver mais'; // Altera o texto do botão para "Ver mais"
                        allAssistirVisible = false;
                    }
                });
            }
        });
    </script>
</body>