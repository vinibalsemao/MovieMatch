<?php
include_once '../style/header.php';
include_once '../../Model/conecta_bd.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$descricao = "";

$sql = "SELECT descricao FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $descricao = $user['descricao'];
}
$stmt->close();
?>
<style>
    .form-container {
        margin: 50px auto;
        width: 650px;
        max-width: 100%;
        /* padding: 30px; */
    }

    .form-container h1 {
        text-align: center;
        margin-bottom: 20px;
    }

    .form-container .form-group {
        position: relative;
        margin-bottom: 30px;
    }

    .form-container input,
    .form-container textarea {
        width: 650px;
        padding: 20px;
        border: none;
        border-radius: 20px;
        font-size: 1em;
        resize: none;
    }

    .form-container textarea {
        height: 150px;
    }

    .form-container input:focus~label,
    .form-container textarea:focus~label,
    .form-container input:valid~label,
    .form-container textarea:valid~label {
        font-size: 0.8em;
        color: white;
        top: -11px;
        left: 25px;
        background-color: orange;
        padding: 3px 8px;
        font-weight: bold;
    }

    .form-container input:focus,
    .form-container textarea:focus {
        outline: none;
    }

    .btn-submit {
        width: 650px;
        color: #fff;
        font-size: 1.2em;
        text-align: center;
        padding: 15px 35px;
        border-radius: 60px;
        display: inline-block;
        background-color: orange;
        cursor: pointer;
        box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.06), 0 2px 10px 0 rgba(0, 0, 0, 0.07);
        transition: all 300ms ease;
        border: none;
    }


    .btn-submit:hover {
        background-color: #ff7f00;
        transform: translateY(-5px);
    }

    @media (max-width: 768px) {
        .form-container {
            width: 100%;
        }

        .btn-submit {
            width: 100%;
        }
    }
</style>

<body>
    <div class="form-container">
        <h1>Adicionar ou Editar <b style="color: orange">Descrição</b></h1>

        <form method="post" action="../../Controller/adiciona_descricao.php">
            <div class="form-group">
                <textarea name="descricao" id="descricao"><?= htmlspecialchars($descricao) ?></textarea>
            </div>
            <input type="submit" class="btn-submit" value="Salvar Descrição">
        </form>
    </div>
</body>

</html>