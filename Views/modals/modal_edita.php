<div class="modal fade" id="modal_edita" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title w-100 text-center">Atualize suas <b style="color: orange">Informações Pessoais</b></h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $id_usuario = $_SESSION['id_usuario'];
                $sql = "SELECT * FROM usuarios WHERE id_usuario = '$id_usuario'";
                $result = mysqli_query($conn, $sql);
                $user = mysqli_fetch_assoc($result);
                ?>

                <form method="post" action="../../Controller/editar_perfil.php" id="edit-profile-form">
                    <div class="form-container">
                        <div class="form-group">
                            <div class="style-form-input full">
                                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($user['nome']) ?>" />
                                <label>Nome</label>
                                <small class="error-message" id="nome-error"></small>
                            </div>

                            <div class="style-form-input full">
                                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" style="background-color: #7d9bb6" readonly />
                                <label>E-mail</label>
                                <small class="error-message" id="email-error"></small>
                            </div>

                            <div class="style-form-input full">
                                <input type="password" name="senha" id="senha" />
                                <label>Nova Senha (deixe em branco se não quiser mudar)</label>
                                <small class="error-message" id="senha-error"></small>
                            </div>

                            <div class="style-form-input full">
                                <input type="password" name="confirma_senha" id="confirma_senha" />
                                <label>Confirme a Nova Senha</label>
                                <small class="error-message" id="confirma_senha-error"></small>
                            </div>

                            <div class="style-form-input full">
                                <input type="submit" class="btn-submit" value="Atualizar" name="submit" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
