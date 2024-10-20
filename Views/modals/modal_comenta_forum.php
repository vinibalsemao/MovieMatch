<div class="modal fade" id="modal_comenta_forum_<?= $forum['id_forum'] ?>" tabindex="-1" role="dialog" aria-labelledby="modal_comenta_forumLabel_<?= $forum['id_forum'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title w-100 text-center" id="modal_comenta_forumLabel_<?= $forum['id_forum'] ?>"><?= htmlspecialchars($forum['titulo']) ?></h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="../../Controller/comentar_topico.php" method="POST">
                    <div class="form-container">
                        <div class="form-group">
                            <input type="hidden" name="id_forum" value="<?= $forum['id_forum'] ?>">
                            <input type="hidden" name="id_usuario" value="<?= $_SESSION['id_usuario'] ?>">
                            <div class="form-group">
                                <div class="style-form-input full">
                                    <label>Conteúdo</label>
                                    <textarea name="resposta" id="resposta" rows="5" class="form-control" style="background-color: #485a6b; color: white;"></textarea>
                                    <small class="error-message" id="conteudo-error"></small>
                                </div>
                                <div class="style-form-input full">
                                    <input type="submit" class="btn-submit" value="Enviar" name="submit" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <br><br>
                    <div class="forum-comments">
                        <h4 style="margin-top: 50px;">Comentários</h4>
                        <?php
                        $query_comments = "
        SELECT r.id_resposta, r.resposta, r.fk_usuario AS usuario_id, u.nome, u.foto, u.id_usuario 
        FROM respostas_foruns r 
        JOIN usuarios u ON r.fk_usuario = u.id_usuario 
        WHERE r.fk_forum = " . $forum['id_forum'] . " 
        ORDER BY r.data_resposta ASC";
                        $comments_result = mysqli_query($conn, $query_comments);

                        if (mysqli_num_rows($comments_result) > 0) {
                            while ($comment = mysqli_fetch_assoc($comments_result)) {
                                $comment_user_foto = !empty($comment['foto']) ? htmlspecialchars($comment['foto']) : 'user.png';
                        ?>
                                <div class="comment-item">
                                    <div class="comment" style="width: 760px; background-color: black;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <img src="../uploads/<?= $comment_user_foto ?>" alt="Foto do Usuário" class="profile-image" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;">
                                                <span>
                                                    <a href="perfil_usuario.php?id_usuario=<?= $comment['id_usuario'] ?>">
                                                        <strong style="color: orange"><?= htmlspecialchars($comment['nome']); ?></strong>
                                                    </a>
                                                </span>
                                            </div>

                                            <?php if (isset($_SESSION['id_usuario']) && ($comment['usuario_id'] == $_SESSION['id_usuario'] || $_SESSION['admin'] == 1)) : ?>
                                                <div class="ml-auto">
                                                    <a href="../../Controller/excluir_comentario_forum.php?id_resposta=<?= $comment['id_resposta'] ?>&id_forum=<?= $forum['id_forum'] ?>" class="small fas fa-trash" style="color: grey" title="Excluir comentário"></a>
                                                </div>
                                            <?php else : ?>
                                                <div class="ml-auto">
                                                    <a href="../../Controller/denunciar_comentario.php?id_resposta=<?= $comment['id_resposta'] ?>" class="small fa fa-exclamation-triangle" style="color: grey" title="Denunciar comentário"></a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <hr style="background-color: grey;">
                                        <p style="margin-left: 10px"><?= nl2br(htmlspecialchars($comment['resposta'])); ?></p>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<p>Nenhum comentário encontrado.</p>";
                        }
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>