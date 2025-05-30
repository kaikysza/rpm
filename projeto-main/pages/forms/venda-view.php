<?php
include_once '../../classe/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RPM Wear | Visualizar Vendas</title>
    <!--  BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!--  CSS -->
    <link href="../../src/css/forms.css" rel="stylesheet">
    <!--  AJUSTES DE CSS-->
    <style>
        .form-control {
            background-color: transparent;
            border: none;
            padding: 0;
            color: #495057;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Visualizar vendas
                            <!--  Botão para voltar a página de vendas cadastrados -->
                            <a href="../venda-realizada.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <!--  Consulta no banco de dados da venda através do ID, caso a venda exista, os dados serão carregados na variavel $venda -->
                        <?php
                        if (isset($_GET['id'])) {
                            $venda_id = mysqli_real_escape_string($conexao, $_GET['id']);
                            $sql = "
                                SELECT v.*, c.nome AS cliente_nome, f.nome AS funcionario_nome
                                FROM vendas v
                                LEFT JOIN clientes c ON v.cliente_id = c.id
                                LEFT JOIN funcionarios f ON v.funcionario_id = f.id
                                WHERE v.id='$venda_id'
                            ";
                            $query = mysqli_query($conexao, $sql);
                            if (mysqli_num_rows($query) > 0) {
                                $venda = mysqli_fetch_array($query);
                        ?>
                                <!--  Passando os dados consultados do banco de dados para visualização -->
                                <div class="mb-3">
                                    <label>Data da Venda</label>
                                    <p class="form-control">
                                        <?= date('d/m/Y', strtotime($venda['data_venda'])); ?>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label>Forma de pagamento</label>
                                    <p class="form-control"><?= $venda['forma_pagamento']; ?></p>
                                </div>
                                <div class="mb-3">
                                    <label>Cliente</label>
                                    <p class="form-control"><?= $venda['cliente_nome']; ?></p>
                                </div>
                                <div class="mb-3">
                                    <label>Vendedor</label>
                                    <p class="form-control"><?= $venda['funcionario_nome']; ?></p>
                                </div>

                                <!--  Exibindo os produtos da venda -->
                                <div class="mb-3">
                                    <h5>Produtos Comprados</h5>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Produto</th>
                                                <th>Gênero</th>
                                                <th>Tamanho</th>
                                                <th>Quantidade</th>
                                                <th>Preço Unitário</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Buscar os itens da venda com gênero e tamanho
                                            $itens_sql = "
                                                SELECT i.*, p.nome AS produto_nome, p.genero, p.tamanho
                                                FROM itens i
                                                LEFT JOIN produtos p ON i.produto_id = p.id
                                                WHERE i.venda_id = '$venda_id'
                                            ";
                                            $itens_query = mysqli_query($conexao, $itens_sql);
                                            $valor_total = 0;
                                            if (mysqli_num_rows($itens_query) > 0) {
                                                while ($item = mysqli_fetch_array($itens_query)) {
                                                    $total_item = $item['preco_venda'] * $item['quantidade'];
                                                    $valor_total += $total_item; // Somando o valor total
                                            ?>
                                                    <tr>
                                                        <td><?= $item['produto_nome']; ?></td>
                                                        <td><?= $item['genero']; ?></td>
                                                        <td><?= $item['tamanho']; ?></td>
                                                        <td><?= $item['quantidade']; ?></td>
                                                        <td>R$ <?= number_format($item['preco_venda'], 2, ',', '.'); ?></td>
                                                        <td>R$ <?= number_format($total_item, 2, ',', '.'); ?></td>
                                                    </tr>
                                            <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="6" class="text-center">Nenhum produto encontrado</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!--  Exibindo o valor total da venda -->
                                <div class="mb-3">
                                    <label><strong>Valor Total da Venda</strong></label>
                                    <p class="form-control">R$ <?= number_format($valor_total, 2, ',', '.'); ?></p>
                                </div>

                        <?php
                            } else {
                                echo "<h5>Venda não encontrada</h5>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>