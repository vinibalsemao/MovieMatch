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

    $foto = $user['foto']; 
    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "../images/";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                $foto = basename($_FILES["foto"]["name"]);
            } else {
                $_SESSION['erro'] = "Erro ao fazer upload da imagem.";
            }
        } else {
            $_SESSION['erro'] = "O arquivo não é uma imagem.";
        }
    }

    if (!empty($senha)) {
        $senha = md5($senha);
        $sql = "UPDATE usuarios SET nome = '$nome', email = '$email', senha = '$senha', foto = '$foto' WHERE id_usuario = '$id_usuario'";
    } else {
        $sql = "UPDATE usuarios SET nome = '$nome', email = '$email', foto = '$foto' WHERE id_usuario = '$id_usuario'";
    }

    if (mysqli_query($conn, $sql)) {
        $_SESSION['sucesso'] = "Perfil atualizado com sucesso!";
        header("Location: ../Views/pages/perfil.php");
    } else {
        $_SESSION['erro'] = "Erro ao atualizar perfil: " . mysqli_error($conn);
        header("Location: ../Views/pages/editar_perfil.php");
    }
}
