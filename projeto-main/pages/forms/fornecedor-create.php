<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RPM Wear | Cadastrar Fornecedores</title>
  <!--  BOOTSTRAP -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!--  CSS -->
  <link href="../../src/css/forms.css" rel="stylesheet">
</head>

<body>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Cadastrar fornecedor
              <!--  Botão para voltar a página de fornecedores cadastrados -->
              <a href="../fornecedor.php" class="btn btn-danger float-end">Voltar</a>
            </h4>
          </div>
          <div class="card-body">
            <!--  Criação do formulário com a classe (acoes) -->
            <form action="../../classe/acoes.php" method="POST">
              <div class="mb-3">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite o nome completo" required>
              </div>
              <div class="mb-3">
                <label for="cnpj">CNPJ/CPF</label>
                <input type="text" id="cnpj" name="cnpj" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="telefone">Telefone</label>
                <input type="text" id="telefone" name="telefone" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="descricao">Descrição</label>
                <input type="text" id="descricao" name="descricao" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Status</label>
                <div class="cargo-options d-flex">
                  <div class="me-3">
                    <input type="radio" id="ativo" name="status" value="Ativo" required>
                    <label for="ativo">Ativo</label>
                  </div>
                  <div class="me-3">
                    <input type="radio" id="inativo" name="status" value="Inativo" required>
                    <label for="inativo">Inativo</label>
                  </div>
                </div>
              </div>
              <div class="mb-3 btn-center">
                <!--  Atribuição da função create_fornecedor da classe (acoes) no botão de cadastrar -->
                <button type="submit" name="create_fornecedor" class="btn btn-primary">Cadastrar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>