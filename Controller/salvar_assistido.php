<?php
session_start();
include_once '../Model/conecta_bd.php';

if (isset($_POST['id_usuario']) && isset($_POST['id_filme_api'])) {
    $userId = $_POST['id_usuario'];
    $movieId = $_POST['id_filme_api'];

    $sql = "INSERT INTO filmes_assistidos (fk_usuario, fk_filme_api) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $movieId);

    if ($stmt->execute()) {
        $_SESSION['sucesso'] = "Filme adicionado aos assistidos!";
        header("Location: ../Views/pages/detalhes_filmes.php?id=" . $movieId);
        exit();
    } else {
        $_SESSION['sucesso'] = "Erro ao adicionar aos assistidos: " . $stmt->error;
        header("Location: ../Views/pages/detalhes_filmes.php?id=" . $movieId);
        exit();
    }
}
