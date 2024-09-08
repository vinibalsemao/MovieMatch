<?php
session_start();
include_once '../Model/conecta_bd.php';  

if (isset($_POST['id_usuario']) && isset($_POST['id_filme_api'])) {
    $userId = $_POST['id_usuario'];
    $movieId = $_POST['id_filme_api'];

    $sql = "DELETE FROM filmes_favoritos WHERE fk_usuario = ? AND fk_filme_api = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $userId, $movieId);
        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "Filme removido dos favoritos!";
        } else {
            $_SESSION['sucesso'] = "Erro ao remover dos favoritos: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['sucesso'] = "Erro ao preparar a declaração: " . $conn->error;
    }
    $conn->close();
    header("Location: ../Views/pages/detalhes_filmes.php?id=" . $movieId);
    exit();
} else {
    $_SESSION['sucesso'] = "Dados incompletos.";
    header("Location: ../Views/pages/detalhes_filmes.php?id=" . $movieId);
    exit();
}
