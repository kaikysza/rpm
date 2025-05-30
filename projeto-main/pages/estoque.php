<?php
include_once '../classe/conexao.php';

// Função para criar as páginas da tabela, limitada para 15 produtos por página.
$limit = 50;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Obter o termo de pesquisa, se existir
$search = isset($_GET['search']) ? mysqli_real_escape_string($conexao, $_GET['search']) : '';

// Consulta para obter produtos agrupados pelo nome e gênero
$sql = "SELECT 
    nome, genero, cor,
    MAX(imagem) AS imagem,  -- Elimina a imagem do agrupamento  
    SUM(CASE WHEN tamanho = 'P' THEN quantidade ELSE 0 END) AS total_P,
    SUM(CASE WHEN tamanho = 'M' THEN quantidade ELSE 0 END) AS total_M,
    SUM(CASE WHEN tamanho = 'G' THEN quantidade ELSE 0 END) AS total_G,
    SUM(CASE WHEN tamanho = 'GG' THEN quantidade ELSE 0 END) AS total_GG
    FROM produtos WHERE nome LIKE '%$search%' 
    GROUP BY nome, genero ORDER BY nome LIMIT $limit OFFSET $offset";

$produtos = mysqli_query($conexao, $sql);

// Contar o total de produtos que correspondem à pesquisa
$count_sql = "SELECT COUNT(*) AS total FROM produtos WHERE nome LIKE '%$search%'";
$result_count = mysqli_query($conexao, $count_sql);
$row = mysqli_fetch_assoc($result_count);
$total_produtos = $row['total'];
$total_pages = ceil($total_produtos / $limit);

// Consulta para contar roupas masculinas 
$count_masculinas_sql = "SELECT SUM(quantidade) AS total_masculinas FROM produtos WHERE genero = 'Masculino'";
$result_masculinas = mysqli_query($conexao, $count_masculinas_sql);
$row_masculinas = mysqli_fetch_assoc($result_masculinas);
$total_masculinas = $row_masculinas['total_masculinas'];

// Consulta para contar roupas femininas
$count_femininas_sql = "SELECT SUM(quantidade) AS total_femininas FROM produtos WHERE genero = 'Feminino'";
$result_femininas = mysqli_query($conexao, $count_femininas_sql);
$row_femininas = mysqli_fetch_assoc($result_femininas);
$total_femininas = $row_femininas['total_femininas'];

// Consulta para contar o total de produtos disponíveis no estoque
$total_produtos_sql = "SELECT SUM(quantidade) AS total FROM produtos";
$result_total = mysqli_query($conexao, $total_produtos_sql);
$row_total = mysqli_fetch_assoc($result_total);
$total_produtos2 = $row_total['total'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RPM Wear | Estoque</title>
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="../src/css/table.css" rel="stylesheet">
</head>
<!-- AJUSTES DE CSS -->
<style>
    .product-image {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border: 2px solid #cccccc;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s;
    }

    .product-image:hover {
        transform: scale(1.05);
    }

    .bg-custom {
        background-color: #191970;
    }

    .pagination {
        margin-bottom: 18px;
        justify-content: center;
    }
</style>

<body>
    <!-- Importação da Sidebar -->
    <?php include('../src/template/sidebar.php'); ?>
    <section class="home-section">
        <div class="container mt-4">
            <!-- Importação do método para exibir a mensagem -->
            <?php include('../classe/mensagem.php'); ?>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-center border-2">
                        <div class="card-header fw-bold 
                            <?= $total_masculinas < 50 ? 'bg-danger text-white' : ($total_masculinas < 200 ? 'bg-warning' : 'bg-custom text-white') ?>">
                            Total de Roupas Masculinas
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= $total_masculinas ?? 0 ?></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center border-2">
                        <div class="card-header fw-bold 
                            <?= $total_femininas < 50 ? 'bg-danger text-white' : ($total_femininas < 200 ? 'bg-warning' : 'bg-custom text-white') ?>">
                            Total de Roupas Femininas
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= $total_femininas ?? 0 ?></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center border-2">
                        <div class="card-header fw-bold 
                            <?= $total_produtos2 < 50 ? 'bg-danger text-white' : ($total_produtos2 < 200 ? 'bg-warning' : 'bg-custom text-white') ?>">
                            Total de Roupas no Estoque
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= $total_produtos2 ?? 0 ?></h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Estoque de produtos</h4>
                            <!-- Formulário de pesquisa no cabeçalho da tabela -->
                            <form method="GET" class="mt-3" id="searchForm">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" id="searchInput" placeholder="Pesquisar produtos..." value="<?= isset($search) ? htmlspecialchars($search) : '' ?>">
                                    <button class="search-button" type="submit"><i class='bx bx-search-alt-2'></i></button>
                                    <button type="button" class="clean-button" onclick="clearSearch()"><i class='bx bx-x-circle'></i></button>
                                    <input type="hidden" name="page" value="<?= htmlspecialchars($page) ?>">
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Imagem</th>
                                        <th>Produto</th>
                                        <th>Gênero</th>
                                        <th>Cor</th>
                                        <th>P</th>
                                        <th>M</th>
                                        <th>G</th>
                                        <th>GG</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($produtos) > 0) {
                                        foreach ($produtos as $produto) {
                                    ?>
                                            <tr>
                                                <td data-label="Imagem" class="text-center">
                                                    <img src="../src/<?= $produto['imagem'] ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" class="product-image">
                                                </td>
                                                <td data-label="Produto"><?= htmlspecialchars($produto['nome']) ?></td>
                                                <td data-label="Gênero"><?= htmlspecialchars($produto['genero']) ?></td>
                                                <td data-label="Cor"><?= htmlspecialchars($produto['cor']) ?></td>
                                                <td data-label="Tamanho P"><?= $produto['total_P'] ?? 0 ?></td>
                                                <td data-label="Tamanho M"><?= $produto['total_M'] ?? 0 ?></td>
                                                <td data-label="Tamanho G"><?= $produto['total_G'] ?? 0 ?></td>
                                                <td data-label="Tamanho GG"><?= $produto['total_GG'] ?? 0 ?></td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="8" class="text-center">Nenhum produto encontrado</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
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
        <?php include('../src/template/footer.php'); ?>
    </section>
    <!-- Scripts do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('searchForm').submit();
        }
    </script>
</body>

</html>