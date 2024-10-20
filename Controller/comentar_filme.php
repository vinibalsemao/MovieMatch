<?php
session_start();
include '../Model/conecta_bd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = isset($_SESSION['id_usuario']) ? intval($_SESSION['id_usuario']) : 0;  // Obtendo o ID do usuário da sessão
    $movieApiId = isset($_POST['movie_id']) ? intval($_POST['movie_id']) : 0;
    $comment = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';

    // Verificando se o comentário não está vazio
    if ($userId && $movieApiId && !empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO comentarios (fk_usuario, fk_filme_api, texto) VALUES (?, ?, ?)");
        $stmt->bind_param('iis', $userId, $movieApiId, $comment);

        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "Comentário enviado com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao inserir comentário: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['erro'] = "Não é possível enviar um comentário vazio!";
    }

    // Redirecionando de volta para a página de detalhes do filme
    header("Location: ../Views/pages/detalhes_filmes.php?id=$movieApiId");
    exit;
} else {
    echo "Método de requisição inválido.";
}

$conn->close();
