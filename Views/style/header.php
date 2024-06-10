<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMatch</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/input_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="apple-touch-icon" sizes="57x57" href="../images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="../images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon/favicon-16x16.png">
    <link rel="manifest" href="../images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#FF500">
    <meta name="msapplication-TileImage" content="../images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#FF500">

    <script>
        function fecharMensagem() {
            document.getElementById("fechar-mensagem").parentElement.parentElement.style.display = 'none';
        }
    </script>

    <style>
        .alert {
            max-width: 600px;
            margin: 0 auto;
            padding: 1rem;
            border-radius: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .alert p {
            margin: 0;
            flex-grow: 1;
            text-align: center;
        }

        .alert-danger {
            background-color: #485a6b;
            border: 1px solid #1e232a;
            color: white;
        }

        .alert-success {
            background-color: #485a6b;
            border: 1px solid #1e232a;
            color: white;
        }

        #fechar-mensagem {
            color: white;
            cursor: pointer;
            font-size: 1.5rem;
            line-height: 1;
        }
    </style>
</head>

<body>
    <header class="navbar navbar-expand-lg text-white">
        <a class="navbar-brand" href="home_page.php" style="color: orange">
            <i class="fas fa-film"></i>
            <b>MovieMatch</b>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="#" style="color: white">Filmes</a></li>
                <li class="nav-item"><a class="nav-link" href="#" style="color: white">Fórum</a></li>
                <li class="nav-item"><a class="nav-link" href="#" style="color: white">Sobre</a></li>

                <?php if (isset($_SESSION['id_usuario'])) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: orange;">
                            <?php echo $_SESSION['nome']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="editar_perfil.php">Editar Perfil</a>
                            <a class="dropdown-item" href="perfil.php">Meus Filmes</a>
                            <hr class="m-0">
                            <a class="dropdown-item" href="../../Controller/logout.php">Sair</a>
                        </div>
                    </li>
                <?php else : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: orange;">
                            Conta
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="login.php">Login</a>
                            <a class="dropdown-item" href="cadastro.php">Cadastro</a>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <?php
    if (isset($_SESSION['erro'])) {
        echo "<br>
        <div class='alert alert-danger'>
            <p> $_SESSION[erro] <span id='fechar-mensagem' onclick='fecharMensagem()'>&times;</span> </p>
        </div> ";
        unset($_SESSION['erro']);
    } elseif (isset($_SESSION['sucesso'])) {
        echo "<br>
        <div class='alert alert-success'>
            <p> $_SESSION[sucesso] <span id='fechar-mensagem' onclick='fecharMensagem()'>&times;</span>  </p>
        </div> ";
        unset($_SESSION['sucesso']);
    }
    ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>