<?php
include_once '../../classe/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RPM Wear | Visualizar Fornecedores</title>
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
                        <h4>Visualizar fornecedor
                            <!--  Botão para voltar a página de fornecedores cadastrados -->
                            <a href="../fornecedor.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <!--  Consulta no banco de dados do fornecedor através do ID, caso o fornecedor exista, os dados serão carregados na variavel $fornecedor -->
                        <?php
                        if (isset($_GET['id'])) {
                            $fornecedor_id = mysqli_real_escape_string($conexao, $_GET['id']);
                            $sql = "SELECT * FROM fornecedores WHERE id='$fornecedor_id'";
                            $query = mysqli_query($conexao, $sql);
                            if (mysqli_num_rows($query) > 0) {
                                $fornecedor = mysqli_fetch_array($query);

                                // Função para formatar cnpj
                                function formatar_cnpj($cnpj)
                                {
                                    return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);
                                }
                                // Função para formatar Telefone
                                function formatar_telefone($telefone)
                                {
                                    return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
                                }
                        ?>
                                <!--  Passando os dados consultados do banco de dados para visualização -->
                                <div class="mb-3">
                                    <label>Nome</label>
                                    <p class="form-control">
                                        <?= $fornecedor['nome']; ?>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label>CPNJ</label>
                                    <p class="form-control">
                                        <?= formatar_cnpj($fornecedor['cnpj']); ?>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label>Telefone</label>
                                    <p class="form-control">
                                        <?= formatar_telefone($fornecedor['telefone']); ?>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label>Descrição</label>
                                    <p class="form-control">
                                        <?= $fornecedor['descricao']; ?>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label>Status</label>
                                    <p class="form-control">
                                        <?= $fornecedor['status']; ?>
                                    </p>
                                </div>
                        <?php
                            } else {
                                echo "<h5>Fornecedor não encontrado</h5>";
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