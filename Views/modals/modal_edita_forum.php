<?php if (isset($forum)) : ?>
    <div class="modal fade" id="modal_edita_forum_<?= $forum['id_forum'] ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title w-100 text-center">Edite seu <b style="color: orange">tópico no fórum</b></h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="../../Controller/editar_topico.php" id="edit-topic-form">
                        <div class="form-container">
                            <div class="form-group">
                                <div class="style-form-input full">
                                    <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($forum['titulo']) ?>" />
                                    <label>Título</label>
                                    <small class="error-message" id="nome-error"></small>
                                </div>
                                <div class="style-form-input full">
                                    <label>Conteúdo</label>
                                    <textarea name="conteudo" id="conteudo" rows="5" class="form-control" style="background-color: #485a6b; color: white;"><?= htmlspecialchars($forum['descricao']) ?></textarea>
                                    <small class="error-message" id="conteudo-error"></small>
                                </div>
                                <input type="hidden" name="id_forum" value="<?= $forum['id_forum'] ?>">
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
<?php endif; ?>