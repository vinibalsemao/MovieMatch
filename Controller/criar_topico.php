<?php
session_start();
include '../Model/conecta_bd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = isset($_SESSION['id_usuario']) ? intval($_SESSION['id_usuario']) : 0;  // Obtendo o ID do usuário da sessão
    $titulo = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $descricao = isset($_POST['conteudo']) ? trim($_POST['conteudo']) : '';

    if ($userId && !empty($titulo) && !empty($descricao)) {
        $stmt = $conn->prepare("INSERT INTO foruns (fk_usuario, titulo, descricao, data_criacao) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param('iss', $userId, $titulo, $descricao);

        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "Tópico criado com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao criar tópico: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['erro'] = "Todos os campos são obrigatórios!";
    }

    header("Location: ../Views/pages/forum.php");
    exit;
} else {
    echo "Método de requisição inválido.";
}

$conn->close();
