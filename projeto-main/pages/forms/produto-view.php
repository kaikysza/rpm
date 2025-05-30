<?php
include_once '../../classe/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RPM Wear | Visualizar Produtos</title>
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- CSS -->
    <link href="../../src/css/forms.css" rel="stylesheet">
    <!-- AJUSTES DE CSS -->
    <style>
        .form-control {
            background-color: transparent;
            border: none;
            padding: 0;
            color: #495057;
        }

        .form-control img {
            width: 300px;
            height: 350px;
            border: 3px solid #007bff;
            border-radius: 8px;
            margin-bottom: 0px;
        }

        .product-details {
            display: flex;
            gap: 10px;
        }

        .column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .columns {
            display: flex;
            gap: 40px;
            margin-left: -750px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Visualizar produto
                            <a href="../produto.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($_GET['id'])) {
                            $produto_id = mysqli_real_escape_string($conexao, $_GET['id']);
                            $sql = "SELECT * FROM produtos WHERE id='$produto_id'";
                            $query = mysqli_query($conexao, $sql);

                            if (mysqli_num_rows($query) > 0) {
                                $produto = mysqli_fetch_array($query);

                                $fornecedor_id = $produto['fornecedor_id'];
                                $sql_fornecedor = "SELECT nome FROM fornecedores WHERE id='$fornecedor_id'";
                                $query_fornecedor = mysqli_query($conexao, $sql_fornecedor);
                                $fornecedor_nome = "Fornecedor não encontrado";

                                if ($query_fornecedor && mysqli_num_rows($query_fornecedor) > 0) {
                                    $fornecedor = mysqli_fetch_array($query_fornecedor);
                                    $fornecedor_nome = $fornecedor['nome'];
                                }
                        ?>
                                <div class="product-details">
                                    <!-- Coluna da imagem -->
                                    <div class="form-control">
                                        <img src="../../src/<?= $produto['imagem']; ?>" alt="Imagem do Produto">
                                    </div>

                                    <!-- Colunas de detalhes do produto -->
                                    <div class="d-flex">
                                        <div class="columns">
                                            <div class="column">
                                                <div>
                                                    <label>Nome</label>
                                                    <p class="form-control"><?= $produto['nome']; ?></p>
                                                </div>
                                                <div>
                                                    <label>Cor</label>
                                                    <p class="form-control"><?= $produto['cor']; ?></p>
                                                </div>
                                                <div>
                                                    <label>Gênero</label>
                                                    <p class="form-control"><?= $produto['genero']; ?></p>
                                                </div>
                                            </div>
                                            <div class="column ms-4">
                                                <div>
                                                    <label>Tamanho</label>
                                                    <p class="form-control"><?= $produto['tamanho']; ?></p>
                                                </div>
                                                <div>
                                                    <label>Quantidade</label>
                                                    <p class="form-control"><?= $produto['quantidade']; ?></p>
                                                </div>
                                                <div>
                                                    <label>Preço</label>
                                                    <p class="form-control"><?= $produto['preco']; ?></p>
                                                </div>
                                            </div>
                                            <div class="column ms-4">
                                                <div>
                                                    <label>Fornecedor</label>
                                                    <p class="form-control"><?= $fornecedor_nome; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            } else {
                                echo "<h5>Produto não encontrado</h5>";
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