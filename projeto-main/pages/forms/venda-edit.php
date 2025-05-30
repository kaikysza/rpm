<?php
session_start();
include_once '../../classe/conexao.php';
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RPM Wear | Editar Vendas</title>
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS -->
    <link href="../../src/css/forms.css" rel="stylesheet">
    <style>
        .cargo-options {
            display: flex;
            gap: 20px;
        }

        .remove-btn {
            cursor: pointer;
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Editar Venda
                            <!-- Botão para voltar a página de vendas -->
                            <a href="../venda-realizada.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
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
                                <!-- Formulário de edição de venda -->
                                <form action="../../classe/acoes.php" method="POST">
                                    <input type="hidden" name="venda_id" value="<?= $venda['id'] ?>">

                                    <!-- Campos para editar a venda (data, forma de pagamento, etc.) -->
                                    <div class="mb-3">
                                        <label>Data da Venda</label>
                                        <input type="date" name="data_venda" class="form-control" value="<?= $venda['data_venda'] ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="forma_pagamento">Forma de Pagamento</label>
                                        <select id="forma_pagamento" name="forma_pagamento" class="form-control" required>
                                            <option value="Dinheiro" <?= $venda['forma_pagamento'] === 'Dinheiro' ? 'selected' : ''; ?>>Dinheiro</option>
                                            <option value="Cartão de Crédito" <?= $venda['forma_pagamento'] === 'Cartão de Crédito' ? 'selected' : ''; ?>>Cartão de Crédito</option>
                                            <option value="Cartão de Débito" <?= $venda['forma_pagamento'] === 'Cartão de Débito' ? 'selected' : ''; ?>>Cartão de Débito</option>
                                            <option value="Pix" <?= $venda['forma_pagamento'] === 'Pix' ? 'selected' : ''; ?>>Pix</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Cliente</label>
                                        <select name="cliente_id" class="form-control" required>
                                            <option value="" disabled>Selecione um Cliente</option>
                                            <?php
                                            $clientes_sql = "SELECT id, nome FROM clientes";
                                            $clientes_result = mysqli_query($conexao, $clientes_sql);
                                            while ($cliente = mysqli_fetch_array($clientes_result)) {
                                                $selected = $cliente['id'] == $venda['cliente_id'] ? 'selected' : '';
                                                echo '<option value="' . $cliente['id'] . '" ' . $selected . '>' . $cliente['nome'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Vendedor</label>
                                        <select name="funcionario_id" class="form-control" required>
                                            <option value="" disabled>Selecione um Vendedor</option>
                                            <?php
                                            // Consulta para selecionar somente vendedores ou gerentes
                                            $vendedores_sql = "SELECT id, nome FROM funcionarios WHERE cargo IN ('Vendedor', 'Gerente')";
                                            $vendedores_result = mysqli_query($conexao, $vendedores_sql);

                                            // Verificando se existem resultados
                                            while ($vendedor = mysqli_fetch_array($vendedores_result)) {
                                                // Verifica se o funcionário é o selecionado
                                                $selected = $vendedor['id'] == $venda['funcionario_id'] ? 'selected' : '';
                                                echo '<option value="' . $vendedor['id'] . '" ' . $selected . '>' . $vendedor['nome'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>


                                    <!-- Produtos da venda -->
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
                                                <th>Remover</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Buscar os itens da venda
                                            $itens_sql = "
                                                SELECT i.*, p.nome AS produto_nome, p.genero, p.tamanho
                                                FROM itens i
                                                LEFT JOIN produtos p ON i.produto_id = p.id
                                                WHERE i.venda_id = '$venda_id'
                                            ";
                                            $itens_query = mysqli_query($conexao, $itens_sql);
                                            while ($item = mysqli_fetch_array($itens_query)) {
                                            ?>
                                                <tr>
                                                    <td><?= $item['produto_nome']; ?></td>
                                                    <td><?= $item['genero']; ?></td>
                                                    <td><?= $item['tamanho']; ?></td>
                                                    <td><input type="number" name="quantidade[<?= $item['id']; ?>]" value="<?= $item['quantidade']; ?>" class="form-control" min="1" required></td>
                                                    <td>R$ <?= number_format($item['preco_venda'], 2, ',', '.'); ?></td>
                                                    <td>R$ <?= number_format($item['preco_venda'] * $item['quantidade'], 2, ',', '.'); ?></td>
                                                    <td>
                                                        <!-- Botão para marcar como removido -->
                                                        <input type="checkbox" name="remover_produto[<?= $item['id']; ?>]" value="1">
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                    <div class="mb-3 btn-center">
                                        <!--  Atribuição da função update-venda da classe (acoes) no botão de editar -->
                                        <button type="submit" name="update_venda" class="btn btn-primary">Editar Venda</button>
                                    </div>
                                </form>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>