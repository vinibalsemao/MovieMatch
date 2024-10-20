<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['erro'] = "Faça login para acessar esta página!";
    header("Location: login.php");
    exit();
}

include_once '../../Model/conecta_bd.php';
include_once '../style/header.php';

$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT * FROM usuarios WHERE id_usuario = '$id_usuario'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

$sql = "SELECT fk_filme_api FROM filmes_favoritos WHERE fk_usuario = $id_usuario";

$result = mysqli_query($conn, $sql);
$savedMovieIds = [];
while ($row = mysqli_fetch_assoc($result)) {
    $savedMovieIds[] = $row['fk_filme_api'];
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

$sqlAssistidos = "SELECT fk_filme_api FROM filmes_assistidos WHERE fk_usuario = $id_usuario ORDER BY data_assistido DESC";
$resultAssistidos = mysqli_query($conn, $sqlAssistidos);
$assistidos = [];

while ($row = mysqli_fetch_assoc($resultAssistidos)) {
    $assistidos[] = $row['fk_filme_api'];
}
?>

<style>
    <?php
    include_once '../style/input_style.css';
    ?>.movie {
        margin: 10px;
    }

    .movie:hover {
        transform: scale(1.0);
    }

    .user-image {
        width: 250px;
        height: 250px;
        object-fit: cover;
        border: none;
    }

    .hidden-favoritos,
    .hidden-assistir,
    .hidden-assistidos {
        display: none;
    }
</style>

<body>
    <main class="container">
        <div class="py-5 text-center">
            <h1>Perfil de <b style="color: orange"><?= htmlspecialchars($user['nome']) ?></b></h1>
            <p class="font-italic">Aqui estão suas informações e os filmes que você mais gostou de assistir.</p>
        </div>

        <hr style="background-color: orange">

        <section class="perfil-usuario mt-5">
            <div class="row">
                <div class="col-md-4">
                    <?php
                    if (!empty($user['foto'])) {
                        $foto = htmlspecialchars($user['foto']);
                    } else {
                        $foto = "user.png";
                    }
                    ?>
                    <img src="../uploads/<?= $foto ?>" class="user-image" alt="Foto do Usuário" width="250px" height="250px">
                    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#modal_foto" style="width: 250px;">
                        <?php
                        if (!empty($user['foto'])) {
                            echo "Atualizar foto";
                        } else {
                            echo "Adicionar foto";
                        }
                        ?>
                    </button>

                    <?php
                    $querySeguidores = "SELECT COUNT(*) AS total_seguidores FROM amigos WHERE fk_usuario2 = $id_usuario";
                    $resultSeguidores = mysqli_query($conn, $querySeguidores);
                    $seguidoresData = mysqli_fetch_assoc($resultSeguidores);
                    $totalSeguidores = $seguidoresData['total_seguidores'];
                    ?>

                    <p>
                        <br>
                        <b>E-mail:</b> <?= htmlspecialchars($user['email']) ?>
                        <br>
                        <b>Seguidores:</b> <a href="#" data-toggle="modal" data-target="#modalSeguidores"><?= $totalSeguidores ?></a>
                        <br>
                        <a href="../../Controller/promover_adm.php">Torne-se administrador</a>
                    </p>
                </div>
                <div class="col-md-5 descricao-container">
                    <h3 style="color: orange">Descrição:</h3>

                    <div class="descricao-texto">
                        <?php
                        if ($user['descricao'] != "") {
                            echo htmlspecialchars($user['descricao']);
                        } else {
                            echo "<p>Você ainda não adicionou uma descrição.</p>";
                        }
                        ?>
                    </div>

                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_descricao" style="background-color: #343a40; border: none; width: 450px;">
                        <?php
                        if ($user['descricao'] != "") {
                            echo "Editar descrição";
                        } else {
                            echo "Adicionar descrição";
                        }
                        ?>
                    </button>
                </div>
            </div>
            <br>
            <a href="#" data-toggle="modal" data-target="#modal_edita">Editar perfil</a>
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

            <div class="container my-5">
                <h1>Filmes Assistidos Recentemente</h1>
                <div class="row">
                    <?php if (count($assistidos) > 0) : ?>
                        <?php
                        $count = 0;
                        foreach ($assistidos as $movieId) {
                            $url = "{$apiUrl}{$movieId}?api_key={$apiKey}&language=pt-BR";
                            $response = file_get_contents($url);
                            if ($response === FALSE) continue;

                            $movie = json_decode($response, true);
                            if (isset($movie['id'])) :
                                $hiddenClass = ($count >= 4) ? 'hidden-assistidos' : '';
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

                        <?php if (count($assistidos) > 4) : ?>
                            <div class="col-12 text-center">
                                <button id="ver-mais-assistidos-btn" class="btn btn-dark" style="width: 100%">Ver mais</button>
                            </div>
                        <?php endif; ?>

                    <?php else : ?>
                        <p style="margin-left: 15px">Você ainda não assistiu a nenhum filme recentemente...</p>
                    <?php endif; ?>
                </div>
            </div>

            <style>
                .hidden-movie {
                    display: none;
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const verMaisAssistidosBtn = document.getElementById('ver-mais-assistidos-btn');
                    const hiddenAssistidos = document.querySelectorAll('.hidden-assistidos');
                    let allAssistidosVisible = false;

                    if (verMaisAssistidosBtn) {
                        verMaisAssistidosBtn.addEventListener('click', function() {
                            if (!allAssistidosVisible) {
                                hiddenAssistidos.forEach(function(movie) {
                                    movie.style.display = 'block';
                                });
                                verMaisAssistidosBtn.textContent = 'Mostrar menos';
                                allAssistidosVisible = true;
                            } else {
                                hiddenAssistidos.forEach(function(movie) {
                                    movie.style.display = 'none';
                                });
                                verMaisAssistidosBtn.textContent = 'Ver mais';
                                allAssistidosVisible = false;
                            }
                        });
                    }

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
        </section>
    </main>

    <br><br><br>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <footer class="text-white text-center py-4 footer">
        <p>&copy; 2024 <a href="#" style="color: orange">MovieMatch</a>. Todos os direitos reservados.</p>
    </footer>

    <?php
    include_once '../modals/modal_descricao.php';
    include_once '../modals/modal_edita.php';
    include_once('../modals/modal_seguidores.php');
    include_once '../modals/modal_foto.php';
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('edit-profile-form');
            const nome = document.getElementById('nome');
            const email = document.getElementById('email');
            const senha = document.getElementById('senha');
            const confirmaSenha = document.getElementById('confirma_senha');

            const nomeError = document.getElementById('nome-error');
            const emailError = document.getElementById('email-error');
            const senhaError = document.getElementById('senha-error');
            const confirmaSenhaError = document.getElementById('confirma_senha-error');

            function validateEmptyField(input, errorElement, message) {
                if (input.value.trim() === '') {
                    errorElement.textContent = message;
                    input.style.borderColor = "red";
                    return false;
                } else {
                    errorElement.textContent = '';
                    input.style.borderColor = "";
                    return true;
                }
            }

            function validatePasswordComplexity(input, errorElement) {
                const password = input.value;
                if (password.length > 0) {
                    const regex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;
                    if (!regex.test(password)) {
                        errorElement.textContent = 'A senha deve conter pelo menos 6 caracteres, incluindo letras e números.';
                        input.style.borderColor = "red";
                        return false;
                    } else {
                        errorElement.textContent = '';
                        input.style.borderColor = "";
                        return true;
                    }
                } else {
                    errorElement.textContent = '';
                    input.style.borderColor = "";
                    return true;
                }
            }

            function validatePasswordMatch(passwordInput, confirmPasswordInput, errorElement) {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    errorElement.textContent = 'As senhas não coincidem.';
                    confirmPasswordInput.style.borderColor = "red";
                    return false;
                } else {
                    errorElement.textContent = '';
                    confirmPasswordInput.style.borderColor = "";
                    return true;
                }
            }

            nome.addEventListener('input', () => validateEmptyField(nome, nomeError, 'O nome é obrigatório.'));
            email.addEventListener('input', () => validateEmptyField(email, emailError, 'O email é obrigatório.'));
            senha.addEventListener('input', () => validatePasswordComplexity(senha, senhaError));
            confirmaSenha.addEventListener('input', () => validatePasswordMatch(senha, confirmaSenha, confirmaSenhaError));

            form.addEventListener('submit', function(event) {
                let isFormValid = true;

                if (!validateEmptyField(nome, nomeError, 'O nome é obrigatório.')) {
                    isFormValid = false;
                }

                if (!validateEmptyField(email, emailError, 'O email é obrigatório.')) {
                    isFormValid = false;
                }

                if (!validatePasswordComplexity(senha, senhaError)) {
                    isFormValid = false;
                }

                if (!validatePasswordMatch(senha, confirmaSenha, confirmaSenhaError)) {
                    isFormValid = false;
                }

                if (!isFormValid) {
                    event.preventDefault();
                }
            });
        });
    </script>


</body>

</html>