<?php
session_start();
include_once '../Model/conecta_bd.php';

if (isset($_POST['id_forum']) && isset($_POST['id_usuario']) && isset($_POST['resposta'])) {
    $forumId = $_POST['id_forum'];
    $userId = $_POST['id_usuario'];
    $comentario = mysqli_real_escape_string($conn, $_POST['resposta']);

    $sql = "INSERT INTO respostas_foruns (fk_forum, fk_usuario, resposta, data_resposta) VALUES (?, ?, ?, NOW())";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iis", $forumId, $userId, $comentario);
        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "Comentário adicionado com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao adicionar o comentário: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['erro'] = "Erro ao preparar a declaração: " . $conn->error;
    }

    $conn->close();
    header("Location: ../Views/pages/forum.php?id=" . $forumId);
    exit();
} else {
    $_SESSION['erro'] = "Dados incompletos.";
    header("Location: ../Views/pages/forum.php");
    exit();
}
?>
