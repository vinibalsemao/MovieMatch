<?php
session_start();
include_once '../Model/conecta_bd.php';

if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['erro'] = "Faça login para acessar esta página!";
    header("Location: ../Views/pages/login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT * FROM usuarios WHERE id_usuario = '$id_usuario'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if ($user['admin'] == 1) {
    $_SESSION['erro'] = "Você já é um administrador!";
    header("Location: ../Views/pages/perfil.php");
    exit();
}

$sql = "UPDATE usuarios SET admin = 1 WHERE id_usuario = '$id_usuario'";
if (mysqli_query($conn, $sql)) {
    $_SESSION['sucesso'] = "Agora você é um administrador!";
    header("Location: ../Views/pages/perfil.php");
} else {
    $_SESSION['erro'] = "Erro ao promover para administrador!";
    header("Location: ../Views/pages/perfil.php");
}
exit();
