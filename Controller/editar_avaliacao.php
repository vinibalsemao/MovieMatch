<?php
session_start();
include_once '../Model/conecta_bd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['movie_id'], $_POST['rating']) && isset($_SESSION['id_usuario'])) {
        $movieId = (int)$_POST['movie_id'];
        $rating = (int)$_POST['rating'];
        $userId = (int)$_SESSION['id_usuario'];

        // Verificar se a avaliação já existe
        $sql_check = "SELECT id_avalia_filme FROM avaliacao_filmes WHERE fk_usuario=? AND fk_filme_api=?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $userId, $movieId);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            // Atualizar a avaliação existente
            $sql_update = "UPDATE avaliacao_filmes SET nota=? WHERE fk_usuario=? AND fk_filme_api=?";
            $stmt_update = $conn->prepare($sql_update);
            if ($stmt_update === false) {
                die('Erro ao preparar a declaração SQL: ' . $conn->error);
            }
            $stmt_update->bind_param("iii", $rating, $userId, $movieId);
            if ($stmt_update->execute()) {
                $_SESSION['sucesso'] = 'Avaliação atualizada com sucesso.';
                header("Location: ../Views/pages/detalhes_filmes.php?id=$movieId");
                exit();
            } else {
                echo "Erro ao atualizar avaliação: " . $stmt_update->error;
            }
        } else {
            echo "Avaliação não encontrada.";
        }
    } else {
        die('Dados insuficientes fornecidos.');
    }
} else {
    die('Método HTTP inválido.');
}
