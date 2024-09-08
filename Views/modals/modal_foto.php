<div class="modal fade" id="modal_foto" tabindex="-1" role="dialog" aria-labelledby="modalFotoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title w-100 text-center">
                    <?php
                    if (!empty($user['foto'])) {
                        echo "Atualizar";
                    } else {
                        echo "Adicionar";
                    }
                    ?> <b style="color: orange">Foto de Perfil</b>
                </h1>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-container">
                    <form method="post" action="../../Controller/adiciona_foto.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="file" name="foto_perfil" accept="image/*" class="form-control-file">
                        </div>
                        <input type="submit" class="btn-submit" value="Salvar Foto">
                    </form>
                </div>
            </div>
        </div>
    </div>