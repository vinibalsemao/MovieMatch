<?php
$id_usuario = $_SESSION['id_usuario'];
$descricao = "";

$sql = "SELECT descricao FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $descricao = $user['descricao'];
}
$stmt->close();
?>

<div class="modal fade" id="modal_descricao" tabindex="-1" role="dialog" aria-labelledby="modalDescricaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title w-100 text-center" id="modalDescricaoLabel"><?php 
                if(!(empty($descricao))) {
                    echo "Atualizar";
                } else {
                    echo "Adicionar";
                }
                ?> <b style="color: orange">Descrição</b></h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-container">
                    <form method="post" action="../../Controller/adiciona_descricao.php">
                        <div class="form-group">
                            <textarea name="descricao" id="descricao" rows="5" class="form-control"><?= htmlspecialchars($descricao ?? '') ?></textarea>
                        </div>
                        <input type="submit" class="btn-submit" value="Salvar Descrição">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>