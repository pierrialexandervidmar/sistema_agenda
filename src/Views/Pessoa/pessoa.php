<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Pessoas</title>
  <script src="../../public/js/jquery371.min.js"></script>
  <script src="../../public/js/pessoa.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

  <div class="container my-5">
    <h3 class="mb-4">Cadastro de Pessoas</h3>

    <!-- Formulário de Cadastro de Pessoa -->
    <form method="POST" id="form-pessoa" class="mb-5">
      <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" autocomplete="off" />
      </div>

      <div class="mb-3">
        <label for="cpf" class="form-label">CPF</label>
        <input type="text" class="form-control" name="cpf" id="cpf" autocomplete="off" />
      </div>

      <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>

    <!-- Tabela de Pessoas Cadastradas -->
    <div class="my-5">
      <table class="table table-striped">
        <button class="btn btn-secondary mb-3" onclick="window.location.href='/contatos'">Voltar</button>
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>CPF</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pessoas as $pessoa): ?>
            <tr>
              <td><?php echo htmlspecialchars($pessoa['id']); ?></td>
              <td><?php echo htmlspecialchars($pessoa['nome']); ?></td>
              <td><?php echo htmlspecialchars($pessoa['cpf']); ?></td>
              <td>
                <!-- Botão de Editar -->
                <button data-id="<?php echo $pessoa['id']; ?>" class="btn btn-primary btn-editar">Editar</button>

                <!-- Botão de Excluir -->
                <form method="POST" style="display:inline;">
                  <input type="hidden" name="id" data-id="<?php echo $pessoa['id']; ?>">
                  <button type="submit" class="btn btn-danger btn-excluir" data-id="<?php echo $pessoa['id']; ?>">Excluir</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>


   <!-- Modal -->
   <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Editar Pessoa</h5>
                </div>
                <div class="modal-body">
                    <form method="POST" id="form-editar-pessoa">
                        <input type="hidden" id="modalId" name="id">
                        <div class="form-group">
                            <label for="modalNome">Nome</label>
                            <input type="text" class="form-control" id="modalNome" name="nome">
                        </div>
                        <div class="form-group">
                            <label for="modalCPF">CPF</label>
                            <input type="text" class="form-control" id="modalCpf" name="cpf">
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




    <!-- Bootstrap JS (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>