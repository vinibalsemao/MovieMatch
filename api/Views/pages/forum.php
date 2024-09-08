<?php
session_start();
include_once '../../Model/conecta_bd.php';
include_once '../style/header.php';

$query = "SELECT * FROM foruns ORDER BY data_criacao DESC";
$result = mysqli_query($conn, $query);

$forums_exist = mysqli_num_rows($result) > 0;

$last_forum = null;
$user_last_forum = null;

if ($forums_exist) {
    $query_last_forum = "SELECT * FROM foruns ORDER BY data_criacao DESC LIMIT 1";
    $last_forum_result = mysqli_query($conn, $query_last_forum);
    $last_forum = mysqli_fetch_assoc($last_forum_result);

    if ($last_forum) {
        $query_user_last_forum = "SELECT nome, foto FROM usuarios WHERE id_usuario = " . $last_forum['fk_usuario'];
        $user_last_forum_result = mysqli_query($conn, $query_user_last_forum);
        $user_last_forum = mysqli_fetch_assoc($user_last_forum_result);
    }
}

$top_users_exist = false;
$top_users_result = null;

if ($forums_exist) {
    $query_top_users = "SELECT usuarios.id_usuario, usuarios.nome, COUNT(foruns.id_forum) as total_foruns 
    FROM usuarios 
    JOIN foruns ON usuarios.id_usuario = foruns.fk_usuario 
    GROUP BY usuarios.id_usuario, usuarios.nome 
    ORDER BY total_foruns DESC 
    LIMIT 5";

    $top_users_result = mysqli_query($conn, $query_top_users);
    $top_users_exist = mysqli_num_rows($top_users_result) > 0;
}
?>

<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    header h1 {
        font-size: 2rem;
    }

    .btn {
        background-color: dark;
        color: #fff;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 5px;
    }

    .content {
        display: flex;
        justify-content: space-between;
    }

    .topics-list {
        flex: 3;
        margin-right: 20px;
    }

    .topic-item {
        background-color: #1c1c1c;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 5px;
    }

    .topic-item h3 {
        margin: 0;
        font-size: 1.2rem;
    }

    .topic-item p {
        margin: 5px 0 0;
        display: none;
    }

    .topic-item .show-description {
        cursor: pointer;
        display: inline-block;
        margin-left: 10px;
        color: orange;
    }

    .topic-stats {
        display: flex;
        font-size: 0.9rem;
        margin-top: 10px;
    }

    aside {
        flex: 1;
        background-color: #1c1c1c;
        padding: 15px;
        border-radius: 5px;
        max-height: 410px;
    }

    .latest-posts,
    .popular-contributors {
        margin-bottom: 20px;
    }

    .latest-posts h2,
    .popular-contributors h2 {
        font-size: 1.2rem;
        margin-bottom: 10px;
    }

    .latest-posts ul,
    .popular-contributors ul {
        list-style: none;
        padding: 0;
    }

    .latest-posts li,
    .popular-contributors li {
        margin-bottom: 10px;
    }

    .topic-info {
        position: relative;
        padding-right: 60px;
    }

    .topic-icons {
        position: absolute;
        top: 0;
        right: 0;
        display: flex;
        gap: 5px;
    }

    .topic-icons span {
        margin: 0;
    }

    footer {
        text-align: center;
        margin-top: 20px;
    }

    <?php include_once '../style/input_style.css'; ?>
</style>

<body>
    <div class="container">
        <header>
            <h1>Forum</h1>
            <?php if (isset($_SESSION['id_usuario'])) { ?>
                <button class="btn btn-dark" id="new-topic-btn" data-toggle="modal" data-target="#modal_forum">Criar tópico</button>
            <?php } ?>
        </header>

        <hr style="background-color: orange">

        <div class="content">
            <div class="topics-list">
                <?php if ($forums_exist) {
                    while ($forum = mysqli_fetch_assoc($result)) {
                        $query = "SELECT * FROM usuarios WHERE id_usuario = " . $forum['fk_usuario'];
                        $userResult = mysqli_query($conn, $query);
                        $user = mysqli_fetch_assoc($userResult);

                        if (!empty($user['foto'])) {
                            $foto = htmlspecialchars($user['foto']);
                        } else {
                            $foto = "user.png";
                        }

                        $dataCriacao = $forum['data_criacao'];

                        $timestamp = strtotime($dataCriacao);

                        $dataFormatada = date('d/m/Y', $timestamp);
                        $horaFormatada = date('H:i', $timestamp);

                        $query_replies = "SELECT COUNT(*) as total FROM respostas_foruns WHERE fk_forum = " . $forum['id_forum'];
                        $replies_result = mysqli_query($conn, $query_replies);
                        $replies = mysqli_fetch_assoc($replies_result);

                        $query_likes = "SELECT COUNT(*) as total FROM curtidas_foruns WHERE fk_forum = " . $forum['id_forum'];
                        $likes_result = mysqli_query($conn, $query_likes);
                        $likes = mysqli_fetch_assoc($likes_result);
                ?>
                        <div class="topic-item">
                            <div class="topic-info">
                                <h3>
                                    <img src="../uploads/<?= $foto ?>" alt="Foto do Usuário" class="profile-image">
                                    <span class="small"><b><a href="perfil_usuario.php?id_usuario=<?= $user['id_usuario'] ?>"><?php echo htmlspecialchars($user['nome']); ?></a></b></span>
                                    -
                                    <span class="small"> <?php echo $dataFormatada . " às " . $horaFormatada; ?></span>
                                    <hr>
                                    <a href="#" style="color: orange" data-toggle="modal"><?php echo htmlspecialchars($forum['titulo']); ?></a>
                                    <span class="fa fa-caret-down show-description" style="color: white"></span>
                                </h3>
                                <p><?php echo htmlspecialchars($forum['descricao']); ?></p>

                                <div class="topic-icons">
                                    <?php if (isset($_SESSION['id_usuario']) && $_SESSION['id_usuario'] == $forum['fk_usuario']) { ?>
                                        <span>
                                            <a href="" class="small fa fa-edit" style="color: grey; margin-top: 10px;" data-toggle="modal" data-target="#modal_edita_forum_<?= $forum['id_forum'] ?>"></a>
                                        </span>
                                        <form method="post" action="../../Controller/excluir_topico.php?" style="display: inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="topic_id" value="<?= $forum['id_forum'] ?>">
                                            <button type="submit" class="btn btn-link p-0"><i class="small fa fa-trash" style="color: grey; margin: 0px;"></i></button>
                                        </form>
                                    <?php } else { ?>
                                        <span><a href="" class="small fa fa-exclamation-triangle" style="color: grey"></a> </span>
                                    <?php } ?>
                                </div>

                            </div>

                            <hr style="background-color: grey;">
                            <div class="topic-stats">
                                <a href="#"> <i class="fa fa-comment"></i> <?php echo $replies['total']; ?> </a>
                                <a href="#" style="margin-left: 10px"> <i class="fa fa-heart"></i> <?php echo $likes['total']; ?> </a>
                            </div>
                        </div>

                        <?php include '../modals/modal_edita_forum.php'; ?>

                    <?php }
                } else { ?>
                    <p>Nenhum fórum encontrado.</p>
                <?php } ?>
            </div>
            <aside>
                <?php if ($last_forum && $user_last_forum) { ?>
                    <div class="latest-posts">
                        <h2>Último Fórum Postado</h2>
                        <ul>
                            <li>
                                <p><a href="#" data-toggle="modal" data-target="#modal_last_forum"><?= htmlspecialchars($last_forum['titulo']) ?></a></p>
                                <img src="../uploads/<?= !empty($user_last_forum['foto']) ? htmlspecialchars($user_last_forum['foto']) : 'user.png' ?>" alt="Foto do Usuário" class="profile-image" style="margin: 0">
                                <span>
                                    <a href="perfil_usuario.php?id_usuario=<?= $last_forum['fk_usuario'] ?>">
                                        <?= htmlspecialchars($user_last_forum['nome']) ?>
                                    </a>
                                </span>
                            </li>
                        </ul>
                    </div>
                <?php } else { ?>
                    <div class="latest-posts">
                        <h2>Último Fórum Postado</h2>
                        <p>Nenhum fórum encontrado.</p>
                    </div>
                <?php } ?>

                <hr style="background-color: orange">

                <?php if ($top_users_exist) { ?>
                    <div class="popular-contributors">
                        <h2>Principais Usuários</h2>
                        <ul>
                            <?php
                            $index = 1;
                            while ($top_user = mysqli_fetch_assoc($top_users_result)) { ?>
                                <li><?= $index ?>.
                                    <a href="perfil_usuario.php?id_usuario=<?= $top_user['id_usuario'] ?>">
                                        <?= htmlspecialchars($top_user['nome']) ?>
                                    </a>
                                    (<?= $top_user['total_foruns'] ?> fóruns)
                                </li>
                            <?php
                                $index++;
                            } ?>
                        </ul>
                    </div>
                <?php } else { ?>
                    <div class="popular-contributors">
                        <h2>Principais Usuários</h2>
                        <p>Nenhum usuário encontrado.</p>
                    </div>
                <?php } ?>
            </aside>
        </div>
    </div>

    <div class="modal fade" id="modal_last_forum" tabindex="-1" role="dialog" aria-labelledby="modal_last_forumLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_last_forumLabel"><?= htmlspecialchars($last_forum['titulo']) ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= htmlspecialchars($last_forum['descricao']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../modals/modal_edita_forum.php'; ?>
    <?php include_once '../modals/modal_forum.php'; ?>

    <br><br><br><br><br><br>

    <footer class="text-white text-center py-4 footer">
        <p>&copy; 2024 <a href="#" style="color: orange">MovieMatch</a>. Todos os direitos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showDescriptionButtons = document.querySelectorAll('.show-description');
            showDescriptionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const description = this.parentElement.nextElementSibling;
                    const icon = this;
                    if (description.style.display === 'none' || description.style.display === '') {
                        description.style.display = 'block';
                        icon.classList.remove('fa-caret-down');
                        icon.classList.add('fa-caret-up');
                    } else {
                        description.style.display = 'none';
                        icon.classList.remove('fa-caret-up');
                        icon.classList.add('fa-caret-down');
                    }
                });
            });
        });
    </script>
</body>

</html>