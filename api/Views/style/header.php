<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieMatch</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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

    <link rel="stylesheet" href="../style/style.css">
    <!-- <link rel="stylesheet" href="../style/input_style.css"> -->
    <link rel="stylesheet" href="../style/modal_style.css">

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

        .profile-image {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 10px;
        }

        .search-bar {
            position: relative;
            max-width: 700px;
            width: 100%;
            margin-right: 5px;
        }

        .search-bar input[type="text"] {
            width: 0;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            border: 1px solid #ccc;
            outline: none;
            transition: width 0.5s ease, opacity 0.5s ease;
            background: #f8f9fa;
            color: black;
            opacity: 0;
            pointer-events: none;
        }

        .fa-search,
        .fa-times {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            cursor: pointer;
            font-size: 1.2rem;
            transition: opacity 0.5s ease;
        }

        #search-icon {
            display: inline;
            opacity: 1;
        }

        #close-icon {
            display: none;
            opacity: 0;
        }

        .search-bar.active input[type="text"] {
            width: 100%;
            opacity: 1;
            pointer-events: auto;
        }

        .search-bar.active #close-icon {
            display: inline;
            opacity: 1;
        }

        .search-bar.active #search-icon {
            opacity: 0;
        }
    </style>


    <script>
        function toggleSearch() {
            const searchBar = document.querySelector('.search-bar');
            const searchInput = document.getElementById('search-input');
            const searchIcon = document.getElementById('search-icon');
            const closeIcon = document.getElementById('close-icon');

            if (!searchBar.classList.contains('active')) {
                searchBar.classList.add('active');

                setTimeout(() => {
                    searchInput.style.display = 'inline';
                    closeIcon.style.display = 'inline';
                }, 500);
            } else {
                searchBar.classList.remove('active');
                setTimeout(() => {
                    searchInput.style.display = 'none';
                    closeIcon.style.display = 'none';
                }, 500);
            }
        }
    </script>
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
                <div class="search-bar">
                    <form action="../pages/busca_filmes.php" method="post">
                        <i class="fa fa-search" id="search-icon" onclick="toggleSearch()" style="color: white"></i>
                        <input type="text" id="search-input" name="query" placeholder="Buscar filmes...">
                        <i class="fa fa-times" id="close-icon" onclick="toggleSearch()" style="color: black"></i>
                    </form>
                </div>

                <li class="nav-item"><a class="nav-link" href="filmes_populares.php" style="color: white">Filmes</a></li>
                <li class="nav-item"><a class="nav-link" href="forum.php" style="color: white">Forum</a></li>
                <li class="nav-item"><a class="nav-link" href="#" style="color: white">Sobre</a></li>

                <?php if (isset($_SESSION['id_usuario'])) : ?>

                    <?php
                    include_once '../../Model/conecta_bd.php';

                    $id_usuario = $_SESSION['id_usuario'];
                    $sql = "SELECT * FROM usuarios WHERE id_usuario = '$id_usuario'";
                    $result = mysqli_query($conn, $sql);
                    $user = mysqli_fetch_assoc($result);

                    if (!empty($user['foto'])) {
                        $foto = htmlspecialchars($user['foto']);
                    } else {
                        $foto = "user.png";
                    }
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: orange;">
                            <?php echo $_SESSION['nome']; ?>
                            <img src="../uploads/<?= $foto ?>" alt="Foto do Usuário" class="profile-image">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="filmes_salvos.php">Filmes Salvos</a>
                            <a class="dropdown-item" href="perfil.php">Meu Perfil</a>
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