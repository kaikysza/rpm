<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RPM Wear | Cadastrar Funcionários</title>
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
            <h4>Cadastrar funcionário
              <!--  Botão para voltar a página de funcionários cadastrados -->
              <a href="../funcionario.php" class="btn btn-danger float-end">Voltar</a>
            </h4>
          </div>
          <div class="card-body">
            <!--  Criação do formulário com a classe (acoes) -->
            <form action="../../classe/acoes.php" method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="foto">Carregar Foto</label>
                <input type="file" id="foto" name="foto" class="form-control" accept="image/*" required>
              </div>
              <div class="mb-3">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite o nome completo" required>
              </div>
              <div class="mb-3">
                <label for="data_nascimento">Data de Nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Cargo</label>
                <div class="cargo-options d-flex">
                  <div class="me-3">
                    <input type="radio" id="gerente" name="cargo" value="Gerente" required>
                    <label for="gerente">Gerente</label>
                  </div>
                  <div class="me-3">
                    <input type="radio" id="estoquista" name="cargo" value="Estoquista" required>
                    <label for="estoquista">Estoquista</label>
                  </div>
                  <div class="me-3">
                    <input type="radio" id="vendedor" name="cargo" value="Vendedor" required>
                    <label for="vendedor">Vendedor</label>
                  </div>
                  <div class="me-3">
                    <input type="radio" id="caixa" name="cargo" value="Caixa" required>
                    <label for="caixa">Caixa</label>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="login">Usuário</label>
                <input type="text" id="login" name="login" class="form-control" placeholder="Digite o nome de usuário" required>
              </div>
              <div class="mb-3">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" class="form-control" placeholder="Digite a senha" required>
              </div>
              <div class="mb-3 btn-center">
                <!--  Atribuição da função create_funcionario da classe (acoes) no botão de cadastrar -->
                <button type="submit" name="create_funcionario" class="btn btn-primary">Cadastrar</button>
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