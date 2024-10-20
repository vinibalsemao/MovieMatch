<?php
session_start();
include '../Model/conecta_bd.php';

if (isset($_GET['id']) && isset($_GET['movieId'])) {
    $commentId = intval($_GET['id']);
    $movieId = intval($_GET['movieId']);

    $userId = $_SESSION['id_usuario'];

    $stmt = $conn->prepare("SELECT fk_usuario FROM comentarios WHERE id_comentario = ? AND fk_usuario = ?");
    $stmt->bind_param('ii', $commentId, $userId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();

        $deleteStmt = $conn->prepare("DELETE FROM comentarios WHERE id_comentario = ?");
        $deleteStmt->bind_param('i', $commentId);

        if ($deleteStmt->execute()) {
            $_SESSION['sucesso'] = "Comentário excluído com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao excluir comentário: " . $deleteStmt->error;
        }

        $deleteStmt->close();
    } else {
        $_SESSION['erro'] = "Você não tem permissão para excluir este comentário.";
    }

} else {
    $_SESSION['erro'] = "Dados inválidos para exclusão.";
}

$conn->close();

header("Location: ../Views/pages/detalhes_filmes.php?id=$movieId");
exit;
?>
