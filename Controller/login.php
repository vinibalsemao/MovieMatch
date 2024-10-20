<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (isset($_POST["submit"])) {

    include_once '../Model/conecta_bd.php';

    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $senha = mysqli_real_escape_string($conn, $_POST["senha"]);

    $senha = md5($senha);

    $senhaRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

    if (empty($email) || empty($senha)) {
        $_SESSION['erro'] = "Preencha todos os campos!";
        header("Location: ../Views/pages/login.php?error=emptyFields");
        exit();
    } else {
        $sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['admin'] = $user['admin'];
            $_SESSION['nome'] = $user['nome'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['foto'] = $user['foto'];
            $_SESSION['sucesso'] = "Login realizado com sucesso!";
            header("Location: ../Views/pages/home_page.php");
        } else {
            $_SESSION['erro'] = "Email ou senha incorretos!";
            header("Location: ../Views/pages/login.php?error=loginError");
            exit();
        }
    }
}
