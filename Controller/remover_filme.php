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

$userId = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : null;
$movieId = isset($_POST['id_filme_api']) ? (int)$_POST['id_filme_api'] : null;

if ($userId && $movieId) {
    if (removeSavedMovie($userId, $movieId, $conn)) {
        $_SESSION['sucesso'] = "Filme removido dos salvos!";
    } else {
        $_SESSION['erro'] = "Erro ao remover o filme dos salvos.";
    }
} else {
    $_SESSION['erro'] = "Dados inválidos.";
}
header("Location: ../Views/pages/filmes_salvos.php");
exit;
