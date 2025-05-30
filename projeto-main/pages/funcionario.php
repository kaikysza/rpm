<?php
include_once '../classe/conexao.php';

// Obter o termo de pesquisa, se existir
$search = isset($_GET['search']) ? mysqli_real_escape_string($conexao, $_GET['search']) : '';

// Defina o limite de usuários por página
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Contar o total de usuários que correspondem à pesquisa
$count_sql = "SELECT COUNT(*) AS total FROM funcionarios WHERE nome LIKE '%$search%'";
$result_count = mysqli_query($conexao, $count_sql);
$row = mysqli_fetch_assoc($result_count);
$total_funcionarios = $row['total'];

$total_pages = ceil($total_funcionarios / $limit);

// Adicione a consulta para buscar os usuários com base no termo de pesquisa
$sql = "SELECT * FROM funcionarios WHERE nome LIKE '%$search%' LIMIT $limit OFFSET $offset";
$result = mysqli_query($conexao, $sql);
$funcionarios = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RPM Wear | Funcionários</title>
  <!--  BOOTSTRAP -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!--  ICONES BOOTSTRAP -->
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <!--  CSS -->
  <link href="../src/css/table.css" rel="stylesheet">
</head>

<body>
  <!--  Importação da Sidebar -->
  <?php include('../src/template/sidebar.php'); ?>
  <section class="home-section">

    <div class="container mt-4">
      <!--  Importação do método para exibir a mensagem -->
      <?php include('../classe/mensagem.php'); ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Funcionários Cadastrados
                <a href="./forms/funcionario-create.php" class="btn btn-primary float-end">Adicionar funcionário</a>
              </h4>
              <form method="GET" class="mt-3" id="searchForm">
                <div class="input-group">
                  <input type="text" class="form-control" name="search" id="searchInput" placeholder="Pesquisar funcionários..." value="<?= isset($search) ? htmlspecialchars($search) : '' ?>">
                  <button class="search-button" type="submit"><i class='bx bx-search-alt-2'></i></button>
                  <button type="button" class="clean-button" onclick="clearSearch()"><i class='bx bx-x-circle'></i></button>
                  <input type="hidden" name="page" value="<?= htmlspecialchars($page) ?>">
                </div>
              </form>
            </div>
            <div class="card-body">
              <!--  Cabeçalho da tabela -->
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Data Nascimento</th>
                    <th>Cargo</th>
                    <th>Login</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <!--  Alimentação dos dados do banco de dados na tabela -->
                  <?php
                  if (mysqli_num_rows($result) > 0) {
                    foreach ($funcionarios as $funcionario) {
                  ?>
                      <tr>
                        <td data-label="ID"><?= $funcionario['id'] ?></td>
                        <td data-label="Nome"><?= $funcionario['nome'] ?></td>
                        <td data-label="Data Nascimento"><?= date('d/m/Y', strtotime($funcionario['data_nascimento'])) ?></td>
                        <td data-label="Cargo"><?= $funcionario['cargo'] ?></td>
                        <td data-label="Login"><?= $funcionario['login'] ?></td>
                        <td data-label="Ações">
                          <!--  Botões de ações na tabela -->
                          <a href="./forms/funcionario-view.php?id=<?= $funcionario['id'] ?>" class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></span>&nbsp;Visualizar</a>
                          <a href="./forms/funcionario-edit.php?id=<?= $funcionario['id'] ?>" class="btn btn-success btn-sm"><span class="bi-pencil-fill"></span>&nbsp;Editar</a>
                          <form action="../classe/acoes.php" method="POST" class="d-inline">
                            <!--  Chamada do método delete_funcionario no botão de deletar -->
                            <button onclick="return confirm('Tem certeza que deseja excluir?')" type="submit" name="delete_funcionario" value="<?= $funcionario['id'] ?>" class="btn btn-danger btn-sm">
                              <span class="bi-trash3-fill"></span>&nbsp;Excluir
                            </button>
                          </form>
                        </td>
                      </tr>
                  <?php
                    }
                  } else {
                    echo '<tr><td colspan="6" class="text-center">Nenhum funcionário encontrado</td></tr>';
                  }
                  ?>
                </tbody>
              </table>
              <!--  Navegador de páginas da tabela -->
              <nav>
                <ul class="pagination justify-content-center">
                  <?php if ($page > 1): ?>
                    <li class="page-item">
                      <a class="page-link" href="?page=<?= $page - 1 ?>">Anterior</a>
                    </li>
                  <?php endif; ?>
                  <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                      <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                  <?php endfor; ?>
                  <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                      <a class="page-link" href="?page=<?= $page + 1 ?>">Próxima</a>
                    </li>
                  <?php endif; ?>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include('../src/template/footer.php'); ?>
  </section>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script>
    function clearSearch() {
      document.getElementById('searchInput').value = '';
      document.getElementById('searchForm').submit();
    }
  </script>
</body>

</html>