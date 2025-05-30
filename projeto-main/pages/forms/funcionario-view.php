<?php
include_once '../../classe/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RPM Wear | Visualizar Funcionários</title>
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

        .form-control img {
            width: 300px;
            height: 300px;
            border: 3px solid #007bff;
            border-radius: 8px;
        }

        .users-details {
            display: flex;
            gap: 20px;
        }

        .users-details .d-flex {
            margin-left: -900px;
        }

        .column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .columns {
            display: flex;
            gap: 40px;
            margin-left: 130px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Visualizar funcionário
                            <!--  Botão para voltar a página de funcionários cadastrados -->
                            <a href="../funcionario.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <!--  Consulta no banco de dados do funcionario através do ID, caso o funcionario exista, os dados serão carregados na variavel $funcionario -->
                        <?php
                        if (isset($_GET['id'])) {
                            $funcionario_id = mysqli_real_escape_string($conexao, $_GET['id']);
                            $sql = "SELECT * FROM funcionarios WHERE id='$funcionario_id'";
                            $query = mysqli_query($conexao, $sql);
                            if (mysqli_num_rows($query) > 0) {
                                $funcionario = mysqli_fetch_array($query);
                        ?>
                                <div class="users-details">
                                    <!-- Coluna da imagem -->
                                    <div class="form-control">
                                        <img src="../../src/uploads/<?= $funcionario['foto']; ?>" alt="Imagem do Funcionário">
                                    </div>

                                    <!-- Colunas de detalhes do funcionario -->
                                    <div class="d-flex">
                                        <div class="columns">
                                            <div class="column">
                                                <div>
                                                    <label>Nome</label>
                                                    <p class="form-control"><?= $funcionario['nome']; ?></p>
                                                </div>
                                                <div>
                                                    <label>Data de Nascimento</label>
                                                    <p class="form-control">
                                                        <!--  Formatação da exibição da data de nascimento recuperada do banco de dados -->
                                                        <?= date('d/m/Y', strtotime($funcionario['data_nascimento'])); ?>
                                                    </p>
                                                </div>
                                                <div>
                                                    <label>Cargo</label>
                                                    <p class="form-control"><?= $funcionario['cargo']; ?></p>
                                                </div>
                                                <div>
                                                    <label>Usuário</label>
                                                    <p class="form-control"><?= $funcionario['login']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            } else {
                                echo "<h5>Funcionário não encontrado</h5>";
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