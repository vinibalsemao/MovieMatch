<?php
session_start();
include_once '../Model/conecta_bd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['movie_id'], $_POST['rating']) && isset($_SESSION['id_usuario'])) {
        $movieId = (int)$_POST['movie_id'];
        $rating = (int)$_POST['rating'];
        $userId = (int)$_SESSION['id_usuario'];

        $sql = "INSERT INTO avaliacao_filmes (fk_usuario, fk_filme_api, nota) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('Erro ao preparar a declaração SQL: ' . $conn->error);
        }

        $stmt->bind_param("iii", $userId, $movieId, $rating);

        if ($stmt->execute()) {
            $_SESSION['sucesso'] = 'Avaliação salva com sucesso!';
            header("Location: ../Views/pages/detalhes_filmes.php?id=$movieId");
            exit();
        } else {
            echo "Erro ao salvar avaliação: " . $stmt->error;
        }
    } else {
        die('Dados insuficientes fornecidos.');
    }
} else {
    die('Método HTTP inválido.');
}
