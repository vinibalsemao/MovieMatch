<?php
session_start();
include_once '../Model/conecta_bd.php';

function removeSavedMovie($userId, $movieId, $conn)
{
    $sql = "DELETE FROM filmes_salvos WHERE id_usuario = ? AND id_filme_api = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $movieId);
    return $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : null;
    $movieId = isset($_POST['id_filme_api']) ? (int)$_POST['id_filme_api'] : null;

    if ($userId && $movieId) {
        if (removeSavedMovie($userId, $movieId, $conn)) {
            $_SESSION['sucesso'] = "Filme removido dos salvos!";
        } else {
            $_SESSION['sucesso'] = "Erro ao remover o filme dos salvos.";
        }
    } else {
        $_SESSION['sucesso'] = "Dados inválidos.";
    }
    header("Location: ../Views/pages/detalhes_filmes.php?id=$movieId");
    exit;
} else {
    $_SESSION['sucesso'] = "Método de requisição inválido.";
    header("Location: ../Views/pages/filmes_salvos.php");
    exit;
}
