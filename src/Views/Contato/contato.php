<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de contatos</title>
    <script src="../../public/js/jquery371.min.js"></script>
    <script src="../../public/js/contato.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">
    <h3 class="mb-4">Agenda de Contatos</h3>

    <form method="POST" id="form-contato" class="mb-5">

        <div class="mb-3">
            <label for="pessoa" class="form-label">Pessoa:</label>
            <select name="pessoa" id="pessoa" class="form-select">
                <?php foreach ($pessoas as $pessoa): ?>
                    <option value="<?php echo htmlspecialchars($pessoa->getId()); ?>">
                        <?php echo htmlspecialchars($pessoa->getNome()); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo:</label>
            <select name="tipo" id="tipo" class="form-select">
                <option value="1">Email</option>
                <option value="0">Telefone</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição:</label>
            <input type="text" name="descricao" id="descricao" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Adicionar Contato</button>
        <a class="btn btn-secondary" href="/pessoas">Acessar cadastro de pessoas</a>
    </form>


    <!-- Tabela de contatos -->
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Pessoa</th>
                <th>Tipo</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contatos as $contato): ?>
                <tr>
                    <td><?php echo htmlspecialchars($contato['id']); ?></td>
                    <td><?php echo htmlspecialchars($contato['pessoa']['nome']); ?></td>
                    <td><?php echo htmlspecialchars($contato['tipo'] ? 'Email' : 'Telefone'); ?></td>
                    <td><?php echo htmlspecialchars($contato['descricao']); ?></td>
                    <td>
                        <!-- Botão de Editar -->
                        <button data-id="<?php echo $contato['id']; ?>" class="btn btn-primary btn-editar">Editar</button>

                        <!-- Botão de Excluir -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" data-id="<?php echo $contato['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-excluir" data-id="<?php echo $contato['id']; ?>">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Editar Contato</h5>
                </div>
                <div class="modal-body">
                    <form method="POST" id="form-editar-contato">
                        <input type="hidden" id="modalId" name="id">
                        <div class="form-group">
                            <label for="modalDescricao">Descrição</label>
                            <input type="text" class="form-control" id="modalDescricao" name="descricao">
                        </div>
                        <div class="form-group">
                            <label for="modalTipo">Tipo</label>
                            <select class="form-control" id="modalTipo" name="tipo">
                                <option value="1">Email</option>
                                <option value="0">Telefone</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="modalIdPessoa" name="idPessoa" hidden>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-fechar-edicao">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar Edição</button>
                        </div>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>