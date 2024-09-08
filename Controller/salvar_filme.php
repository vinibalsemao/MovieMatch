<?php
session_start();
include_once '../Model/conecta_bd.php'; 

if (isset($_POST['id_usuario']) && isset($_POST['id_filme_api'])) {
    $userId = $_POST['id_usuario'];
    $movieId = $_POST['id_filme_api'];

    $sql = "INSERT INTO filmes_salvos (id_usuario, id_filme_api) VALUES (?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $userId, $movieId);
        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "Filme salvo com sucesso!";
        } else {
            $_SESSION['sucesso'] = "Erro ao salvar o filme: " . $stmt->error;
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
