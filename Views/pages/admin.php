<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['erro'] = "Faça login para acessar esta página!";
    header("Location: login.php");
    exit();
}

include_once '../../Model/conecta_bd.php';
include_once '../style/header.php';

$sql = "SELECT id_usuario, nome, foto, admin FROM usuarios";
$result = mysqli_query($conn, $sql);

?>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background-color: #333;
    }

    table thead th {
        background-color: #555;
        color: white;
        padding: 10px;
        text-align: left;
        border: none;
    }

    table tbody td {
        color: white;
        padding: 10px;
        border: none;
    }

    .user-info {
        display: flex;
        align-items: center;
    }

    .user-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
        margin-right: 15px;
    }

    .admin-badge {
        color: white;
        background-color: green;
        padding: 3px 6px;
        border-radius: 5px;
        font-size: 12px;
        margin-left: 10px;
    }

    .non-admin-badge {
        color: white;
        background-color: red;
        padding: 3px 6px;
        border-radius: 5px;
        font-size: 12px;
        margin-left: 10px;
    }

    table a.btn-primary {
        color: white !important;
        background-color: orange !important;
        text-decoration: none !important;
        padding: 5px 10px !important;
        border-radius: 5px !important;
        border: none !important;
    }

    table a.btn-primary:hover {
        background-color: darkorange !important;
        text-decoration: none !important;
    }

    table a {
        text-decoration: none !important;
        color: white !important;
    }
</style>

<body>
    <main class="container">
        <div class="py-5 text-center">
            <h1>Usuários do <b style="color: orange">MovieMatch</b></h1>
        </div>

        <section>
            <div class="container">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Status</th>
                            <th>Perfil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <?php
                                        $foto = !empty($row['foto']) ? $row['foto'] : 'user.png';
                                        ?>
                                        <img src="../uploads/<?= htmlspecialchars($foto) ?>" alt="Foto de <?= htmlspecialchars($row['nome']) ?>" class="user-image">
                                        <span><?= htmlspecialchars($row['nome']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($row['admin'] == 1) : ?>
                                        <span class="admin-badge">Admin</span>
                                    <?php else : ?>
                                        <span class="non-admin-badge">Usuário</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="perfil_usuario.php?id_usuario=<?= $row['id_usuario'] ?>">Ver Perfil</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <br><br><br>
    <footer class="text-white text-center py-4 footer">
        <p>&copy; 2024 <a href="#" style="color: orange">MovieMatch</a>. Todos os direitos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>