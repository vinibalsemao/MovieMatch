<?php
include_once '../style/header.php';
?>

<style>
    input:focus~label,
    textarea:focus~label,
    input:valid~label,
    textarea:valid~label {
        font-size: 0.8em;
        color: #fff;
        top: -11px;
        -webkit-transition: all 0.225s ease;
        transition: all 0.225s ease;
        background: orange;
        padding: 3px 8px;
        left: 25px;
        border-radius: 50px;
        font-weight: bold;
    }

    .style-form-input {
        float: left;
        width: 295px;
        margin: 1em 0;
        position: relative;
        border-radius: 4px;
    }

    @media only screen and (max-width: 768px) {

        .style-form-input {
            width: 100%;
        }

    }

    .style-form-input label {
        padding: 1.3rem 30px 1rem 30px;
        position: absolute;
        top: 10px;
        left: 0;
        -webkit-transition: all 0.25s ease;
        transition: all 0.25s ease;
        pointer-events: none;
    }

    .style-form-input.full {
        width: 650px;
        max-width: 100%;
    }

    input {
        width: 100%;
        padding: 30px;
        border: 0;
        font-size: 1em;
        background-color: #485A6B;
        color: #fff;
        border-radius: 50px;
    }

    input:focus,
    textarea:focus {
        outline: 0;
    }

    input:focus~span,
    textarea:focus~span {
        width: 100%;
        -webkit-transition: all 0.075s ease;
        transition: all 0.075s ease;
    }

    .input-container {
        width: 650px;
        max-width: 100%;
        margin: 20px auto 25px auto;
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
        -webkit-transition: all 300ms ease;
        transition: all 300ms ease;
        border: none;
    }

    .btn-submit:hover {

        transform: translateY(10px);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.10),
            0 1px 1px 0 rgba(0, 0, 0, 0.09);
        background-color: #ff7f00;
    }

    @media (max-width: 768px) {
        .btn-submit {

            width: 100%;
            float: none;
            text-align: center;

        }
    }
</style>

<body>
    <main class="container">
        <div class="py-5 text-center">
            <h1>Cadastre-se no <b style="color: orange">MovieMatch</b></h1>
            <p class="font-italic">Crie sua conta para começar a compartilhar seu gosto por filmes!</p>
        </div>

        <div class="row justify-content-center">
            <div class="row input-container">
                <form method="post" action="#">

                    <div class="style-form-input full">
                        <input type="text" name="nome" required />
                        <label>Nome</label>
                    </div>

                    <div class="style-form-input full">
                        <input type="email" name="email" required />
                        <label>E-mail</label>
                    </div>

                    <div class="style-form-input full">
                        <input type="password" name="senha" id="senha" required />
                        <label>Senha</label>
                    </div>

                    <div class="style-form-input full">
                        <input type="password" name="confirma_senha" id="confirma_senha" required />
                        <label>Confirme a Senha</label>
                    </div>

                    <div class="style-form-input full">
                        <input type="hidden" name="acao" value="enviar">
                        <button class="btn-submit">Cadastrar</button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>