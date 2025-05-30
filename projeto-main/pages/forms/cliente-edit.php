<?php
session_start();
include_once '../../classe/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RPM Wear | Editar Clientes</title>
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
                        <h4>Editar cliente
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
                        ?>
                                <!--  Criação do formulário com a classe (acoes) -->
                                <!--  Passando os dados consultados do banco de dados para os inputs -->
                                <form action="../../classe/acoes.php" method="POST">
                                    <input type="hidden" name="cliente_id" value="<?= $cliente['id'] ?>">
                                    <div class="mb-3">
                                        <label>Nome</label>
                                        <input type="text" name="nome" value="<?= $cliente['nome'] ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Data de Nascimento</label>
                                        <input type="date" name="data_nascimento" value="<?= $cliente['data_nascimento'] ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>CPF</label>
                                        <input type="text" name="cpf" value="<?= $cliente['cpf'] ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Telefone</label>
                                        <input type="text" name="telefone" value="<?= $cliente['telefone'] ?>" class="form-control">
                                    </div>
                                    <div class="mb-3 btn-center">
                                        <!--  Atribuição da função update_cliente da classe (acoes) no botão de editar -->
                                        <button type="submit" name="update_cliente" class="btn btn-primary">Editar</button>
                                    </div>
                                </form>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>