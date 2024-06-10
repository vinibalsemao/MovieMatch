<?php
session_start();
include_once '../Model/conecta_bd.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova_descricao = htmlspecialchars(trim($_POST['descricao']));

    // Atualiza a descrição no banco de dados
    $sql_update = "UPDATE usuarios SET descricao = ? WHERE id_usuario = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $nova_descricao, $id_usuario);

    if ($stmt_update->execute()) {
        $_SESSION['sucesso'] = "Descrição atualizada com sucesso!";
    } else {
        $_SESSION['erro'] = "Erro ao atualizar a descrição. Por favor, tente novamente.";
    }
    $stmt_update->close();

    header("Location: ../Views/pages/perfil.php");
    exit();
} else {
    header("Location: ../Views/pages/adicionar_descricao.php");
    exit();
}
