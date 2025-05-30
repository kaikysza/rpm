<?php
include_once '../classe/conexao.php';

// Obter o termo de pesquisa, se existir
$search = isset($_GET['search']) ? mysqli_real_escape_string($conexao, $_GET['search']) : '';

// Defina o limite de fornecedores por página
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Contar o total de fornecedores que correspondem à pesquisa
$count_sql = "SELECT COUNT(*) AS total FROM fornecedores WHERE nome LIKE '%$search%'";
$result_count = mysqli_query($conexao, $count_sql);
$row = mysqli_fetch_assoc($result_count);
$total_fornecedores = $row['total'];

$total_pages = ceil($total_fornecedores / $limit);

// Adicione a consulta para buscar os fornecedores com base no termo de pesquisa
$sql = "SELECT * FROM fornecedores WHERE nome LIKE '%$search%' LIMIT $limit OFFSET $offset";
$result = mysqli_query($conexao, $sql);
$fornecedores = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RPM Wear | Fornecedores</title>
    <!--  BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!--  ICONES BOOTSTRAP -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                            <h4>Fornecedores Cadastrados
                                <a href="./forms/fornecedor-create.php" class="btn btn-primary float-end">Adicionar fornecedor</a>
                            </h4>
                            <form method="GET" class="mt-3" id="searchForm">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" id="searchInput" placeholder="Pesquisar fornecedor..." value="<?= isset($search) ? htmlspecialchars($search) : '' ?>">
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
                                        <th>CNPJ/CPF</th>
                                        <th>Telefone</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--  Alimentação dos dados do banco de dados na tabela -->
                                    <?php
                                    if (mysqli_num_rows($result) > 0) {
                                        foreach ($fornecedores as $fornecedor) {
                                            // Verifica o status e define a classe de estilo
                                            $statusClass = ($fornecedor['status'] === 'Ativo') ? 'text-success' : 'text-danger';
                                    ?>
                                            <tr>
                                                <td data-label="ID"><?= $fornecedor['id'] ?></td>
                                                <td data-label="Nome"><?= $fornecedor['nome'] ?></td>
                                                <td data-label="Cnpj" class="cnpj"><?= $fornecedor['cnpj'] ?></td>
                                                <td data-label="Telefone" class="telefone"><?= $fornecedor['telefone'] ?></td>
                                                <td data-label="Status" class="<?= $statusClass ?>"><?= $fornecedor['status'] ?></td>
                                                <td data-label="Ações">
                                                    <!--  Botões de ações na tabela -->
                                                    <a href="./forms/fornecedor-view.php?id=<?= $fornecedor['id'] ?>" class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></span>&nbsp;Visualizar</a>
                                                    <a href="./forms/fornecedor-edit.php?id=<?= $fornecedor['id'] ?>" class="btn btn-success btn-sm"><span class="bi-pencil-fill"></span>&nbsp;Editar</a>
                                                    <form action="../classe/acoes.php" method="POST" class="d-inline">
                                                        <!--  Chamada do método delete_usuario no botão de deletar -->
                                                        <button onclick="return confirm('Tem certeza que deseja excluir?')" type="submit" name="delete_fornecedor" value="<?= $fornecedor['id'] ?>" class="btn btn-danger btn-sm">
                                                            <span class="bi-trash3-fill"></span>&nbsp;Excluir
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="6" class="text-center">Nenhum fornecedor encontrado</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <!--  Navegador de páginas da tabela -->
                            <nav>
                                <ul class="pagination justify-content-center">
                                    <?php if ($page > 1) : ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $page - 1 ?>">Anterior</a>
                                        </li>
                                    <?php endif; ?>
                                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    <?php if ($page < $total_pages) : ?>
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
    <script>
        // Função para formatar CNPJ
        function formatarCNPJ(cnpj) {
            return cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
        }

        // Função para formatar telefone
        function formatarTelefone(telefone) {
            return telefone.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Formatar todos os elementos de CNPJ
            const cnps = document.querySelectorAll('.cnpj'); // Seleciona os elementos com a classe .cnpj
            cnps.forEach(cnpjElement => {
                const cnpj = cnpjElement.textContent.replace(/\D/g, ''); // Remove tudo que não for número
                cnpjElement.textContent = formatarCNPJ(cnpj); // Aplica a formatação no conteúdo do elemento
            });

            // Formatar todos os elementos de telefone
            const telefones = document.querySelectorAll('.telefone'); // Seleciona os elementos com a classe .telefone
            telefones.forEach(telefoneElement => {
                const telefone = telefoneElement.textContent.replace(/\D/g, ''); // Remove tudo que não for número
                telefoneElement.textContent = formatarTelefone(telefone); // Aplica a formatação no conteúdo do elemento
            });
        });
    </script>
</body>

</html>