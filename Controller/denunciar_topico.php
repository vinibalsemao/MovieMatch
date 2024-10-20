<?php
session_start();
include_once '../Model/conecta_bd.php';

if (isset($_POST['forum_id']) && isset($_SESSION['id_usuario'])) {
    $forum_id = $_POST['forum_id'];
    $user_id = $_SESSION['id_usuario'];

    // Verifica se o usuário já denunciou este tópico
    $check_query = "SELECT * FROM denuncias_foruns WHERE fk_usuario = $user_id AND fk_forum = $forum_id";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) == 0) {
        // Inserir nova denúncia
        $insert_query = "INSERT INTO denuncias_foruns (fk_usuario, fk_forum) VALUES ($user_id, $forum_id)";
        mysqli_query($conn, $insert_query);

        // Verifica o total de denúncias
        $count_query = "SELECT COUNT(*) as total FROM denuncias_foruns WHERE fk_forum = $forum_id";
        $count_result = mysqli_query($conn, $count_query);
        $count = mysqli_fetch_assoc($count_result);

        if ($count['total'] >= 2) {
            // Excluir o tópico se houver 5 ou mais denúncias
            $delete_query = "DELETE FROM foruns WHERE id_forum = $forum_id";
            mysqli_query($conn, $delete_query);

            // Excluir as denúncias relacionadas ao tópico
            $delete_denuncias_query = "DELETE FROM denuncias_foruns WHERE fk_forum = $forum_id";
            mysqli_query($conn, $delete_denuncias_query);
        }

        $_SESSION['sucesso'] = 'Denúncia registrada com sucesso.';
    } else {
        $_SESSION['sucesso'] = 'Você já denunciou este tópico.';
    }
} else {
    $_SESSION['sucesso'] = 'Erro ao processar a denúncia.';
}

header("Location: ../views/pages/forum.php");
