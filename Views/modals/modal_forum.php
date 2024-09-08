<div class="modal fade" id="modal_forum" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title w-100 text-center">Adicione um <b style="color: orange">tópico no forum</b></h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-container">
                    <form method="post" action="../../Controller/criar_topico.php">
                        <div class="form-group">
                            <div class="style-form-input full">
                                <input type="text" name="nome" id="nome">
                                <label>Titulo</label>
                                <small class="error-message" id="nome-error"></small>
                            </div>
                            <div class="style-form-input full">
                                <label>Conteúdo</label>
                                <textarea name="conteudo" id="conteudo" rows="5" class="form-control" style="background-color: #485a6b; color: white;"></textarea>
                            </div>
                        </div>

                        <div class="style-form-input full">
                            <input type="submit" class="btn-submit" value="Enviar" name="submit" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>