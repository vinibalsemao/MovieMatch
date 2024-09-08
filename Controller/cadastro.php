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

    // if (empty($nome) || empty($email) || empty($senha) || empty($confirma_senha)) {
    //     $_SESSION['erro'] = "Preencha todos os campos!";
    //     header("Location: ../views/pages/cadastro.php?error=emptyFields");
    //     exit();
    // }

    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['erro'] = "Email já cadastrado!";
        header("Location: ../views/pages/cadastro.php?error=emailError");
        exit();
    }

    // if ($senha !== $confirma_senha) {
    //     $_SESSION['erro'] = "As senhas não coincidem!";
    //     header("Location: ../views/pages/cadastro.php?error=passwordError");
    //     exit();
    // }

    $senha = md5($senha);

    $foto_perfil = "";
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['foto_perfil']['tmp_name'];
        $fileName = $_FILES['foto_perfil']['name'];
        $fileSize = $_FILES['foto_perfil']['size'];
        $fileType = $_FILES['foto_perfil']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = '../Views/uploads/';
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $foto_perfil = $newFileName;
            } else {
                $_SESSION['erro'] = "Erro ao mover o arquivo para o diretório de upload.";
                header("Location: ../views/pages/cadastro.php?error=uploadError");
                exit();
            }
        } else {
            $_SESSION['erro'] = "Tipo de arquivo não permitido. Apenas JPG, JPEG, PNG e GIF são permitidos.";
            header("Location: ../views/pages/cadastro.php?error=invalidFileType");
            exit();
        }
    }

    $sql = "INSERT INTO usuarios (nome, email, senha, foto) VALUES ('$nome', '$email', '$senha', '$foto_perfil')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
        header("Location: ../views/pages/login.php?success=registerSuccess");
    } else {
        $_SESSION['erro'] = "Erro ao se cadastrar!";
        header("Location: ../views/pages/cadastro.php?error=registerError");
        exit();
    }
}
