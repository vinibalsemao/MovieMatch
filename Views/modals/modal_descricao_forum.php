<div class="modal fade" id="modal_descricao_forum_<?= $forum['id_forum'] ?>" tabindex="-1" role="dialog" aria-labelledby="modal_descricao_forum_<?= $forum['id_forum'] ?>Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_forum_<?= $forum['id_forum'] ?>Label"><?= htmlspecialchars($forum['titulo']) ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= htmlspecialchars($forum['descricao']) ?>
            </div>
        </div>
    </div>
</div>