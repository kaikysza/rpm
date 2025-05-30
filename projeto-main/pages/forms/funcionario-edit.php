<?php
session_start();
include_once '../../classe/conexao.php';
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RPM Wear | Editar Funcionários</title>
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
                        <h4>Editar funcionário
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
                                <!--  Criação do formulário com a classe (acoes) -->
                                <!--  Passando os dados consultados do banco de dados para os inputs -->
                                <form action="../../classe/acoes.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="funcionario_id" value="<?= $funcionario['id'] ?>">
                                    <div class="mb-3">
                                        <label for="foto">Carregar Foto</label>
                                        <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
                                        <br>
                                        <img src="../../src/uploads/<?= $funcionario['foto']; ?>" alt="Imagem atual" style="max-width: 100px; height: auto;">
                                    </div>
                                    <div class="mb-3">
                                        <label>Nome</label>
                                        <input type="text" name="nome" value="<?= $funcionario['nome'] ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Data de Nascimento</label>
                                        <input type="date" name="data_nascimento" value="<?= $funcionario['data_nascimento'] ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Cargo</label>
                                        <div class="cargo-options">
                                            <div>
                                                <input type="radio" id="gerente" name="cargo" value="Gerente" <?= $funcionario['cargo'] === 'Gerente' ? 'checked' : ''; ?> required>
                                                <label for="gerente">Gerente</label>
                                            </div>
                                            <div>
                                                <input type="radio" id="estoquista" name="cargo" value="Estoquista" <?= $funcionario['cargo'] === 'Estoquista' ? 'checked' : ''; ?> required>
                                                <label for="estoquista">Estoquista</label>
                                            </div>
                                            <div>
                                                <input type="radio" id="vendedor" name="cargo" value="Vendedor" <?= $funcionario['cargo'] === 'Vendedor' ? 'checked' : ''; ?> required>
                                                <label for="vendedor">Vendedor</label>
                                            </div>
                                            <div>
                                                <input type="radio" id="caixa" name="cargo" value="Caixa" <?= $funcionario['cargo'] === 'Caixa' ? 'checked' : ''; ?> required>
                                                <label for="caixa">Caixa</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label>Usuário</label>
                                        <input type="text" name="login" value="<?= $funcionario['login'] ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Senha</label>
                                        <input type="password" name="senha" class="form-control">
                                    </div>
                                    <div class="mb-3 btn-center">
                                        <!--  Atribuição da função update_funcionario da classe (acoes) no botão de editar -->
                                        <button type="submit" name="update_funcionario" class="btn btn-primary">Editar</button>
                                    </div>
                                </form>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>