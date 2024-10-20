<?php
session_start();
include_once '../Model/conecta_bd.php';

if (isset($_GET['id_resposta']) && isset($_GET['id_forum'])) {
    $id_resposta = $_GET['id_resposta'];
    $id_forum = $_GET['id_forum'];

    $sql = "SELECT fk_usuario FROM respostas_foruns WHERE id_resposta = '$id_resposta'";
    $result = mysqli_query($conn, $sql);
    $comment = mysqli_fetch_assoc($result);

    if ($comment['fk_usuario'] == $_SESSION['id_usuario'] || $_SESSION['admin'] == 1) {
        $sql_delete = "DELETE FROM respostas_foruns WHERE id_resposta = '$id_resposta'";
        if (mysqli_query($conn, $sql_delete)) {
            $_SESSION['sucesso'] = "Comentário excluído com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao excluir comentário!";
        }
    } else {
        $_SESSION['erro'] = "Você não tem permissão para excluir este comentário!";
    }
    header("Location: ../Views/pages/forum.php?id_forum=$id_forum");
    exit();
} else {
    $_SESSION['erro'] = "Comentário não encontrado!";
    header("Location: ../Views/pages/perfil.php");
    exit();
}
