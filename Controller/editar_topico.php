<?php
session_start();
include_once '../Model/conecta_bd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_forum = $_POST['id_forum'];
    $titulo = mysqli_real_escape_string($conn, $_POST['nome']);
    $descricao = mysqli_real_escape_string($conn, $_POST['conteudo']);

    if (!empty($id_forum) && !empty($titulo) && !empty($descricao)) {
        $query = "UPDATE foruns SET titulo = '$titulo', descricao = '$descricao' WHERE id_forum = $id_forum AND fk_usuario = {$_SESSION['id_usuario']}";

        if (mysqli_query($conn, $query)) {
            $_SESSION['sucesso'] = "Tópico atualizado com sucesso!";
        } else {
            $_SESSION['sucesso'] = "Erro ao atualizar o tópico. Tente novamente.";
        }
    } else {
        $_SESSION['sucesso'] = "Todos os campos são obrigatórios.";
    }

    header("Location: ../Views/pages/forum.php");
    exit();
} else {
    header("Location: ../Views/pages/forum.php");
    exit();
}
