<?php
include_once '../style/header.php';

if (isset($_SESSION['id_usuario'])) {
    $_SESSION['erro'] = "Você já está logado!";
    header("Location: home_page.php?error=alreadyLoggedIn");
    exit();
}

?>

<body>
    <main class="container">
        <div class="py-5 text-center">
            <h1>Entre no <b style="color: orange">MovieMatch</b></h1>
            <p class="font-italic">Bem vindo de volta! Acesse sua conta e volte a compartilhar seu gosto por filmes!</p>
        </div>

        <div class="row justify-content-center">
            <div class="row input-container">
                <form method="post" action="../../Controller/login.php">


                    <div class="style-form-input full">
                        <input type="email" name="email" />
                        <label>E-mail</label>
                    </div>

                    <div class="style-form-input full">
                        <input type="password" name="senha" id="senha"/>
                        <label>Senha</label>
                    </div>

                    <div class="style-form-input full">
                        <input type="submit" class="btn-submit" value="Entrar" name="submit" />
                    </div>
                </form>
            </div>
        </div>
    </main>

    <br><br><br><br><br><br><br><br><br>

    <footer class="text-white text-center py-4 footer">
        <p>&copy; 2024 <a href="#" style="color: orange">MovieMatch</a>. Todos os direitos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>