<?php
session_start();
include_once '../Model/conecta_bd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['movie_id']) && isset($_SESSION['id_usuario'])) {
        $movieId = (int)$_POST['movie_id'];
        $userId = (int)$_SESSION['id_usuario'];

        $sql = "DELETE FROM avaliacao_filmes WHERE fk_usuario = ? AND fk_filme_api = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Erro ao preparar a declaração SQL: ' . $conn->error);
        }

        $stmt->bind_param("ii", $userId, $movieId);

        if ($stmt->execute()) {
            $_SESSION['sucesso'] = 'Avaliação removida com sucesso.';
            header("Location: ../Views/pages/detalhes_filmes.php?id=$movieId");
            exit();
        } else {
            echo "Erro ao remover avaliação: " . $stmt->error;
        }
    } else {
        die('Dados insuficientes fornecidos.');
    }
} else {
    die('Método de requisição inválido.');
}
