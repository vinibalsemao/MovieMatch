<?php
session_start();
include_once '../Model/conecta_bd.php';

if (isset($_POST['id_amigo'])) {
    $id_usuario = intval($_SESSION['id_usuario']);
    $id_amigo = intval($_POST['id_amigo']);

    if (isset($_POST['deixar_de_seguir'])) {
        // Deixar de seguir: Excluir a relação da tabela de amigos
        $query = "DELETE FROM amigos WHERE 
                  (fk_usuario1 = $id_usuario AND fk_usuario2 = $id_amigo) 
                  OR (fk_usuario1 = $id_amigo AND fk_usuario2 = $id_usuario)";
        mysqli_query($conn, $query);
        header("Location: ../Views/pages/perfil_usuario.php?id_usuario=$id_amigo");
        exit();
    } else {
        // Seguir: Adicionar relação na tabela de amigos
        $query = "INSERT INTO amigos (fk_usuario1, fk_usuario2) VALUES ($id_usuario, $id_amigo)";
        mysqli_query($conn, $query);
        header("Location: ../Views/pages/perfil_usuario.php?id_usuario=$id_amigo");
        exit();
    }
}
?>
