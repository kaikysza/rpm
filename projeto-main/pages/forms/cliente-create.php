<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RPM Wear | Cadastrar Clientes</title>
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
            <h4>Cadastrar cliente
              <!--  Botão para voltar a página de clientes cadastrados -->
              <a href="../cliente.php" class="btn btn-danger float-end">Voltar</a>
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
                <label for="data_nascimento">Data de Nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="telefone">Telefone</label>
                <input type="text" id="telefone" name="telefone" class="form-control" required>
              </div>
              <div class="mb-3 btn-center">
                <!--  Atribuição da função create_cliente da classe (acoes) no botão de cadastrar -->
                <button type="submit" name="create_cliente" class="btn btn-primary">Cadastrar</button>
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