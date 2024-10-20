<?php
session_start();
include('../../Model/conecta_bd.php');

$id_usuario_logado = $_SESSION['id_usuario'];

// Query para buscar todos os filmes (favoritos e assistidos) do usuário logado
$query_user_movies = "
    SELECT fk_filme_api 
    FROM filmes_favoritos 
    WHERE fk_usuario = $id_usuario_logado
    UNION 
    SELECT fk_filme_api 
    FROM filmes_assistidos 
    WHERE fk_usuario = $id_usuario_logado
";
$result_user_movies = mysqli_query($conn, $query_user_movies);
$user_movies = [];

if ($result_user_movies) {
    while ($row = mysqli_fetch_assoc($result_user_movies)) {
        $user_movies[] = $row['fk_filme_api'];
    }
}

if (!empty($user_movies)) {
    $movies_string = implode(',', $user_movies);

    $query_common_users = "
        SELECT u.id_usuario, u.nome, u.foto, COUNT(DISTINCT f_or_a.fk_filme_api) as filmes_em_comum
        FROM (
            SELECT fk_usuario, fk_filme_api FROM filmes_favoritos WHERE fk_filme_api IN ($movies_string)
            UNION
            SELECT fk_usuario, fk_filme_api FROM filmes_assistidos WHERE fk_filme_api IN ($movies_string)
        ) as f_or_a
        JOIN usuarios u ON f_or_a.fk_usuario = u.id_usuario
        WHERE f_or_a.fk_usuario != $id_usuario_logado
        GROUP BY u.id_usuario
        HAVING filmes_em_comum > 0
        ORDER BY filmes_em_comum DESC
    ";
    $result_common_users = mysqli_query($conn, $query_common_users);
} else {
    $result_common_users = false;
}

include_once '../style/header.php';
?>

<style>
    .container {
        max-width: 800px;
        margin: 50px auto;
        background-color: black;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
    }

    .user-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .user-item {
        display: flex;
        align-items: center;
        background-color: #485a6b;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
    }

    .profile-image-list {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 15px;
    }

    .user-details {
        flex-grow: 1;
    }

    .user-details a {
        text-decoration: none;
        color: orange;
        font-weight: bold;
    }

    .common-movies {
        color: white;
    }
</style>
</head>

<body>
    <div class="container">
        <h1>Usuários com <b style="color: orange">Gostos em Comum</b></h1>
        <br>
        <div class="user-list">
            <?php
            if ($result_common_users && mysqli_num_rows($result_common_users) > 0) {
                while ($user = mysqli_fetch_assoc($result_common_users)) {
                    $user_foto = !empty($user['foto']) ? $user['foto'] : 'user.png';
            ?>
                    <div class="user-item">
                        <img src="../uploads/<?= htmlspecialchars($user_foto) ?>" alt="Foto de Perfil" class="profile-image-list">
                        <div class="user-details">
                            <a href="perfil_usuario.php?id_usuario=<?= $user['id_usuario'] ?>">
                                <?= htmlspecialchars($user['nome']) ?>
                            </a>
                            <p class="common-movies">
                                <?= $user['filmes_em_comum'] ?> filme(s) em comum
                            </p>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<div class='text-center'>";
                echo "<p>Nenhum usuário com gostos em comum encontrado.</p>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>