<?php
session_start();
include_once '../../classe/conexao.php';
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RPM Wear | Editar Fornecedores</title>
    <!--  BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--  CSS -->
    <link href="../../src/css/forms.css" rel="stylesheet">
    <!--  AJUSTES DE CSS-->
    <style>
        .cargo-options {
            display: flex;
            gap: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Editar fornecedor
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
                        ?>
                                <!--  Criação do formulário com a classe (acoes) -->
                                <!--  Passando os dados consultados do banco de dados para os inputs -->
                                <form action="../../classe/acoes.php" method="POST">
                                    <input type="hidden" name="fornecedor_id" value="<?= $fornecedor['id'] ?>">
                                    <div class="mb-3">
                                        <label>Nome</label>
                                        <input type="text" name="nome" value="<?= $fornecedor['nome'] ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>CNPJ/CPF</label>
                                        <input type="text" name="cnpj" value="<?= $fornecedor['cnpj'] ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Telefone</label>
                                        <input type="text" name="telefone" value="<?= $fornecedor['telefone'] ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Descrição</label>
                                        <input type="text" name="descricao" value="<?= $fornecedor['descricao'] ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Status</label>
                                        <div class="cargo-options">
                                            <div>
                                                <input type="radio" id="ativo" name="status" value="Ativo" <?= $fornecedor['status'] === 'Ativo' ? 'checked' : ''; ?> required>
                                                <label for="ativo">Ativo</label>
                                            </div>
                                            <div>
                                                <input type="radio" id="inativo" name="status" value="Inativo" <?= $fornecedor['status'] === 'Inativo' ? 'checked' : ''; ?> required>
                                                <label for="inativo">Inativo</label>
                                            </div>
                                            <div>
                                            </div>
                                        </div>
                                        <div class="mb-3 btn-center">
                                            <!--  Atribuição da função update_fornecedor da classe (acoes) no botão de editar -->
                                            <button type="submit" name="update_fornecedor" class="btn btn-primary">Editar</button>
                                        </div>
                                </form>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>