<?php
include_once '../style/header.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Supondo que você tenha uma função para buscar os dados do usuário
include_once '../../Model/conecta_bd.php';

$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT * FROM usuarios WHERE id_usuario = '$id_usuario'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

?>

<body>
    <main class="container">
        <div class="py-5 text-center">
            <h1>Editar Perfil no <b style="color: orange">MovieMatch</b></h1>
            <p class="font-italic">Atualize suas informações pessoais.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="post" action="../../Controller/editar_perfil.php" enctype="multipart/form-data">
                    <!-- <div class="text-center mb-4">
                        <?php
                        $foto = htmlspecialchars($user['foto']);
                        $foto = !empty($foto) ? "../images/$foto" : "../images/user.png";
                        ?>
                        <div class="profile-pic-container">
                            <img src="<?= $foto ?>" class="img-thumbnail profile-pic" alt="Foto do Usuário">
                            <div class="overlay">
                                <input type="file" name="foto" accept="image/*" id="foto" class="inputfile" />
                                <label for="foto" class="btn-upload">
                                    <i class="fas fa-camera"></i> Alterar Foto
                                </label>
                            </div>
                        </div>
                    </div> -->

                    <div class="style-form-input full">
                        <input type="text" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" required />
                        <label>Nome</label>
                    </div>

                    <div class="style-form-input full">
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required />
                        <label>E-mail</label>
                    </div>

                    <div class="style-form-input full">
                        <input type="password" name="senha" id="senha" />
                        <label>Nova Senha (deixe em branco se não quiser mudar)</label>
                    </div>

                    <div class="style-form-input full">
                        <input type="password" name="confirma_senha" id="confirma_senha" />
                        <label>Confirme a Nova Senha</label>
                    </div>

                    <div class="style-form-input full">
                        <input type="submit" class="btn-submit" value="Atualizar" name="submit" />
                    </div>
                </form>
            </div>
        </div>
    </main>

    <br><br>
    
    <footer class="text-white text-center py-4 footer">
        <p>&copy; 2024 <a href="#" style="color: orange">MovieMatch</a>. Todos os direitos reservados.</p>
    </footer>

    <style>
        .profile-pic-container {
            position: relative;
            display: inline-block;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
        }

        .overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: hidden;
            width: 150px;
            height: 0;
            border-radius: 50%;
            transition: .5s ease;
            text-align: center;
        }

        .profile-pic-container:hover .overlay {
            height: 50px;
            border-radius: 0 0 50% 50%;
        }

        .btn-upload {
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            display: inline-block;
        }

        .inputfile {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>