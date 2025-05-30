<?php
include_once '../../classe/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RPM Wear | Visualizar Clientes</title>
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
                        <h4>Visualizar cliente
                            <!--  Botão para voltar a página de clientes cadastrados -->
                            <a href="../cliente.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <!--  Consulta no banco de dados do cliente através do ID, caso o cliente exista, os dados serão carregados na variavel $cliente -->
                        <?php
                        if (isset($_GET['id'])) {
                            $cliente_id = mysqli_real_escape_string($conexao, $_GET['id']);
                            $sql = "SELECT * FROM clientes WHERE id='$cliente_id'";
                            $query = mysqli_query($conexao, $sql);
                            if (mysqli_num_rows($query) > 0) {
                                $cliente = mysqli_fetch_array($query);

                                // Função para formatar CPF
                                function formatar_cpf($cpf)
                                {
                                    return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
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
                                        <?= $cliente['nome']; ?>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label>Data Nascimento</label>
                                    <p class="form-control">
                                        <!--  Formatação da exibição da data de nascimento recuperada do banco de dados -->
                                        <?= date('d/m/Y', strtotime($cliente['data_nascimento'])); ?>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label>CPF</label>
                                    <p class="form-control">
                                        <?= formatar_cpf($cliente['cpf']); ?>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label>Telefone</label>
                                    <p class="form-control">
                                        <?= formatar_telefone($cliente['telefone']); ?>
                                    </p>
                                </div>
                        <?php
                            } else {
                                echo "<h5>Cliente não encontrado</h5>";
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