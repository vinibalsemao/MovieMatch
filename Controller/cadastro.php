<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (isset($_POST["submit"])) {

    include_once '../Model/conecta_bd.php';

    $nome = mysqli_real_escape_string($conn, $_POST["nome"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $senha = mysqli_real_escape_string($conn, $_POST["senha"]);
    $confirma_senha = mysqli_real_escape_string($conn, $_POST["confirma_senha"]);


    if (empty($nome) || empty($email) || empty($senha) || empty($confirma_senha)) {
        $_SESSION['erro'] = "Preencha todos os campos!";
        header("Location: ../views/pages/cadastro.php?error=emptyFields");
        exit();
    } else {
        $query = "SELECT * FROM usuarios WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['erro'] = "Email já cadastrado!";
            header("Location: ../views/pages/cadastro.php?error=emailError");
            exit();
        } else {
            $senha = md5($senha);
            $confirma_senha = md5($confirma_senha);

            if ($senha == $confirma_senha) {
                $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
                    header("Location: ../views/pages/login.php?success=registerSuccess");
                } else {
                    $_SESSION['erro'] = "Erro ao se cadastrar!";
                    header("Location: ../views/pages/cadastro.php?error=registerError");
                    exit();
                }
            } else {
                $_SESSION['erro'] = "As senhas não coincidem!";
                header("Location: ../views/pages/cadastro.php?error=passwordError");
                exit();
            }
        }
    }
}
