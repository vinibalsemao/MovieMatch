<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once '../Model/conecta_bd.php';

if (isset($_POST["submit"])) {

    $id_usuario = $_SESSION['id_usuario'];
    $nome = mysqli_real_escape_string($conn, $_POST["nome"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $senha = mysqli_real_escape_string($conn, $_POST["senha"]);
    $confirma_senha = mysqli_real_escape_string($conn, $_POST["confirma_senha"]);

    if (!empty($senha)) {
        $senha = md5($senha);
        $sql = "UPDATE usuarios SET nome = '$nome', senha = '$senha' WHERE id_usuario = '$id_usuario'";
    } else {
        $sql = "UPDATE usuarios SET nome = '$nome', email = '$email' WHERE id_usuario = '$id_usuario'";
    }

    if (mysqli_query($conn, $sql)) {
        $_SESSION['sucesso'] = "Perfil atualizado com sucesso!";
        header("Location: ../Views/pages/perfil.php");
    } else {
        $_SESSION['erro'] = "Erro ao atualizar perfil: " . mysqli_error($conn);
        header("Location: ../Views/pages/editar_perfil.php");
    }
}
