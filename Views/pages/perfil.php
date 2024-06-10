<?php
include_once '../style/header.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

include_once '../../Model/conecta_bd.php';

$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT * FROM usuarios WHERE id_usuario = '$id_usuario'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

$sql_filmes = "SELECT * FROM filmes_salvos WHERE fk_usuario = '$id_usuario'";
$result_filmes = mysqli_query($conn, $sql_filmes);
$filmes_salvos = mysqli_fetch_all($result_filmes, MYSQLI_ASSOC);
?>
<style>
    a {
        color: white;
        text-decoration: none;
    }

    a:hover {
        color: orange;
        text-decoration: none;
    }

    .perfil-usuario img {
        width: 100%;
        height: auto;
        max-width: 250px;
    }

    .perfil-usuario {
        text-align: left;
    }

    .col-md-5 {
        position: relative;
    }

    .descricao-container {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .descricao-texto {
        margin-bottom: 20px;
    }

    .editar-descricao-link {
        position: absolute;
        top: 310px;
        left: 18px;
    }
</style>

<body>
    <main class="container">
        <div class="py-5 text-center">
            <h1>Perfil de <b style="color: orange"><?= htmlspecialchars($user['nome']) ?></b></h1>
            <p class="font-italic">Aqui estão suas informações e os filmes que você salvou.</p>
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
                    <img src="../images/<?= $foto ?>" class="img-thumbnail" alt="Foto do Usuário">
                    <h3 class="mt-3"><?= htmlspecialchars($user['nome']) ?></h3>
                    <p><b>E-mail:</b> <?= htmlspecialchars($user['email']) ?></p>
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
                    <a href='adiciona_descricao.php' class='editar-descricao-link'>Editar descrição</a>
                </div>
            </div>
            <br>
            <hr style="background-color: orange">

            <a href="editar_perfil.php">Editar perfil</a>
        </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>