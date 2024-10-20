<div class="modal fade" id="modalSeguidores" tabindex="-1" aria-labelledby="modalSeguidoresLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSeguidoresLabel">Seguidores</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <?php
                    $queryListaSeguidores = "SELECT u.id_usuario, u.nome, u.foto FROM amigos a
                                             JOIN usuarios u ON a.fk_usuario1 = u.id_usuario
                                             WHERE a.fk_usuario2 = $id_usuario";
                    $resultListaSeguidores = mysqli_query($conn, $queryListaSeguidores);

                    if (mysqli_num_rows($resultListaSeguidores) > 0) {
                        while ($seguidor = mysqli_fetch_assoc($resultListaSeguidores)) {
                            $fotoSeguidor = !empty($seguidor['foto']) ? htmlspecialchars($seguidor['foto']) : 'user.png';
                    ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: black;">

                                <a href="perfil_usuario.php?id_usuario=<?= $seguidor['id_usuario'] ?>">
                                    <img src="../uploads/<?= $fotoSeguidor ?>" alt="Foto do seguidor" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px">
                                    <?= htmlspecialchars($seguidor['nome']) ?>
                                </a>
                            </li>
                    <?php
                        }
                    } else {
                        echo "<p>Este usuário ainda não possui seguidores.</p>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>