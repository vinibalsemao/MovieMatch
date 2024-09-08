<?php
session_start();

if (isset($_SESSION['id_usuario'])) {
    $_SESSION['erro'] = "Você já está logado!";
    header("Location: home_page.php");
    exit();
}

include_once '../style/header.php';

?>

<style>
    <?php
    include_once '../style/input_style.css';
    ?>
</style>

<body>
    <main class="container">
        <div class="py-5 text-center">
            <h1>Cadastre-se no <b style="color: orange">MovieMatch</b></h1>
            <p class="font-italic">Crie sua conta para começar a compartilhar seu gosto por filmes!</p>
        </div>

        <div class="row justify-content-center">
            <div class="row input-container">
                <form method="post" action="../../Controller/cadastro.php" enctype="multipart/form-data" id="registration-form">
                    <div class="style-form-input full">
                        <input type="text" name="nome" id="nome" />
                        <label>Nome</label>
                        <small class="error-message" id="nome-error"></small>
                    </div>

                    <div class="style-form-input full">
                        <input type="email" name="email" id="email" />
                        <label>E-mail</label>
                        <small class="error-message" id="email-error"></small>
                    </div>

                    <div class="style-form-input full">
                        <input type="password" name="senha" id="senha" />
                        <label>Senha</label>
                        <small class="error-message" id="senha-error"></small>
                    </div>

                    <div class="style-form-input full">
                        <input type="password" name="confirma_senha" id="confirma_senha" />
                        <label>Confirme a Senha</label>
                        <small class="error-message" id="confirma_senha-error"></small>
                    </div>

                    <div class="style-form-input full">
                        <input type="file" name="foto_perfil" />
                        <label>Foto de Perfil (opcional)</label>
                    </div>

                    <div class="style-form-input full">
                        <input type="submit" class="btn-submit" value="Cadastrar" name="submit" />
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="text-white text-center py-4 footer">
        <p>&copy; 2024 <a href="#" style="color: orange">MovieMatch</a>. Todos os direitos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registration-form');
            const nome = document.getElementById('nome');
            const email = document.getElementById('email');
            const senha = document.getElementById('senha');
            const confirmaSenha = document.getElementById('confirma_senha');

            const nomeError = document.getElementById('nome-error');
            const emailError = document.getElementById('email-error');
            const senhaError = document.getElementById('senha-error');
            const confirmaSenhaError = document.getElementById('confirma_senha-error');

            function validateEmptyField(input, errorElement, message) {
                if (input.value.trim() === '') {
                    errorElement.textContent = message;
                    input.style.borderColor = "red";
                    return false;
                } else {
                    errorElement.textContent = '';
                    input.style.borderColor = "";
                    return true;
                }
            }

            function validatePasswordComplexity(input, errorElement) {
                const password = input.value;
                const regex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/; // Mínimo de 6 caracteres, contendo letras e números

                if (!regex.test(password)) {
                    errorElement.textContent = 'A senha deve conter pelo menos 6 caracteres, incluindo letras e números.';
                    input.style.borderColor = "red";
                    return false;
                } else {
                    errorElement.textContent = '';
                    input.style.borderColor = "";
                    return true;
                }
            }

            function validatePasswordMatch(passwordInput, confirmPasswordInput, errorElement) {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    errorElement.textContent = 'As senhas não coincidem.';
                    confirmPasswordInput.style.borderColor = "red";
                    return false;
                } else {
                    errorElement.textContent = '';
                    confirmPasswordInput.style.borderColor = "";
                    return true;
                }
            }

            nome.addEventListener('input', () => validateEmptyField(nome, nomeError, 'O nome é obrigatório.'));
            email.addEventListener('input', () => validateEmptyField(email, emailError, 'O email é obrigatório.'));
            senha.addEventListener('input', () => validatePasswordComplexity(senha, senhaError));
            confirmaSenha.addEventListener('input', () => validatePasswordMatch(senha, confirmaSenha, confirmaSenhaError));

            form.addEventListener('submit', function(event) {
                let isFormValid = true;

                if (!validateEmptyField(nome, nomeError, 'O nome é obrigatório.')) {
                    isFormValid = false;
                }

                if (!validateEmptyField(email, emailError, 'O email é obrigatório.')) {
                    isFormValid = false;
                }

                if (!validatePasswordComplexity(senha, senhaError)) {
                    isFormValid = false;
                }

                if (!validatePasswordMatch(senha, confirmaSenha, confirmaSenhaError)) {
                    isFormValid = false;
                }

                if (!isFormValid) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>

</html>