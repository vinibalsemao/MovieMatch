<?php
session_start();
include_once '../Model/conecta_bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $topic_id = intval($_POST['topic_id']);

        $query = "SELECT fk_usuario FROM foruns WHERE id_forum = $topic_id";
        $result = mysqli_query($conn, $query);
        $forum = mysqli_fetch_assoc($result);

        if ($forum && isset($_SESSION['id_usuario']) && $forum['fk_usuario'] == $_SESSION['id_usuario']) {
            $query_delete = "DELETE FROM foruns WHERE id_forum = $topic_id";
            if (mysqli_query($conn, $query_delete)) {
                $query_delete_respostas = "DELETE FROM respostas_foruns WHERE fk_forum = $topic_id";
                mysqli_query($conn, $query_delete_respostas);

                $query_delete_curtidas = "DELETE FROM curtidas_foruns WHERE fk_forum = $topic_id";
                mysqli_query($conn, $query_delete_curtidas);

                $_SESSION['sucesso'] = "Tópico excluído com sucesso.";
            } else {
                $_SESSION['sucesso'] = "Erro ao excluir o tópico. Por favor, tente novamente.";
            }
        } else {
            $_SESSION['sucesso'] = "Ação não permitida.";
        }
    }
}

header('Location: ../Views/pages/forum.php');
exit;
