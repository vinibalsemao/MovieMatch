<?php
session_start();
include_once '../Model/conecta_bd.php';

if (isset($_POST['id_forum']) && isset($_SESSION['id_usuario'])) {
    $id_forum = $_POST['id_forum'];
    $id_usuario = $_SESSION['id_usuario'];

    // Verificar se o usuário já curtiu o fórum
    $check_query = "SELECT * FROM curtidas_foruns WHERE fk_usuario = ? AND fk_forum = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "ii", $id_usuario, $id_forum);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) === 0) {
        // Inserir a curtida no banco de dados
        $insert_query = "INSERT INTO curtidas_foruns (fk_usuario, fk_forum) VALUES (?, ?)";
        $stmt_insert = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt_insert, "ii", $id_usuario, $id_forum);
        mysqli_stmt_execute($stmt_insert);

        $liked = true;
    } else {
        // Remover a curtida do banco de dados
        $delete_query = "DELETE FROM curtidas_foruns WHERE fk_usuario = ? AND fk_forum = ?";
        $stmt_delete = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt_delete, "ii", $id_usuario, $id_forum);
        mysqli_stmt_execute($stmt_delete);

        $liked = false;
    }

    // Obter o novo total de curtidas
    $count_query = "SELECT COUNT(*) as total FROM curtidas_foruns WHERE fk_forum = ?";
    $stmt_count = mysqli_prepare($conn, $count_query);
    mysqli_stmt_bind_param($stmt_count, "i", $id_forum);
    mysqli_stmt_execute($stmt_count);
    $result = mysqli_stmt_get_result($stmt_count);
    $likes = mysqli_fetch_assoc($result)['total'];

    // Retornar o novo total de curtidas e se o fórum foi curtido ou não
    echo json_encode(['success' => true, 'totalLikes' => $likes, 'liked' => $liked]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao processar a curtida.']);
}
