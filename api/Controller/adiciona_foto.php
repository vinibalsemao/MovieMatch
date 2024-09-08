<?php
session_start();
include_once '../Model/conecta_bd.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['foto_perfil']['tmp_name'];
        $file_name = $_FILES['foto_perfil']['name'];
        $file_size = $_FILES['foto_perfil']['size'];
        $file_type = $_FILES['foto_perfil']['type'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $upload_dir = '../Views/uploads/';
        $dest_path = $upload_dir . $file_name;

        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($file_extension, $allowed_extensions)) {
            if (move_uploaded_file($file_tmp_path, $dest_path)) {
                $sql_update = "UPDATE usuarios SET foto = ? WHERE id_usuario = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("si", $file_name, $id_usuario);

                if ($stmt_update->execute()) {
                    $_SESSION['sucesso'] = "Foto atualizada com sucesso!";
                } else {
                    $_SESSION['erro'] = "Erro ao atualizar a foto. Por favor, tente novamente.";
                }
                $stmt_update->close();
            } else {
                $_SESSION['erro'] = "Erro ao mover o arquivo para o diretório de destino.";
            }
        } else {
            $_SESSION['erro'] = "Formato de arquivo não permitido. Use jpg, jpeg, png ou gif.";
        }
    } else {
        $_SESSION['erro'] = "Erro ao enviar o arquivo. Por favor, tente novamente.";
    }

    header("Location: ../Views/pages/perfil.php");
    exit();
} else {
    header("Location: ../Views/pages/adicionar_foto.php");
    exit();
}
