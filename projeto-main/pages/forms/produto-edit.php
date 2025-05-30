<?php
session_start();
include_once '../../classe/conexao.php';
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RPM Wear | Editar Produtos</title>
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
                        <h4>Editar produtos
                            <!--  Botão para voltar a página de produtos cadastrados -->
                            <a href="../produto.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <!--  Consulta no banco de dados do produto através do ID, caso o produto exista, os dados serão carregados na variavel $produto -->
                        <?php
                        if (isset($_GET['id'])) {
                            $produto_id = mysqli_real_escape_string($conexao, $_GET['id']);
                            $sql = "SELECT * FROM produtos WHERE id='$produto_id'";
                            $query = mysqli_query($conexao, $sql);
                            if (mysqli_num_rows($query) > 0) {
                                $produto = mysqli_fetch_array($query);
                        ?>
                                <!--  Criação do formulário com a classe (acoes) -->
                                <!--  Passando os dados consultados do banco de dados para os inputs -->
                                <form action="../../classe/acoes.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
                                    <div class="mb-3">
                                        <label for="foto">Carregar Foto</label>
                                        <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
                                        <br>
                                        <img src="../../src/<?= $produto['imagem']; ?>" alt="Imagem atual" style="max-width: 100px; height: auto;">
                                    </div>
                                    <div class="mb-3">
                                        <label>Nome</label>
                                        <input type="text" name="nome" value="<?= $produto['nome'] ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cor">Cor</label>
                                        <select id="cor" name="cor" class="form-control" required>
                                            <option value="" disabled selected>Escolha uma cor</option>
                                            <option value="Vermelha">Vermelha</option>
                                            <option value="Azul">Azul</option>
                                            <option value="Amarela">Amarela</option>
                                            <option value="Verde">Verde</option>
                                            <option value="Preta">Preta</option>
                                            <option value="Branca">Branca</option>
                                            <option value="Cinza">Cinza</option>
                                            <option value="Roxo">Roxo</option>
                                            <option value="Laranja">Laranja</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Gênero</label>
                                        <div class="cargo-options d-flex">
                                            <div class="me-3">
                                                <input type="radio" id="masculino" name="genero" value="Masculino" <?= $produto['genero'] === 'Masculino' ? 'checked' : ''; ?> required>
                                                <label for="genero">Masculino</label>
                                            </div>
                                            <div class="me-3">
                                                <input type="radio" id="feminino" name="genero" value="Feminino" <?= $produto['genero'] === 'Feminino' ? 'checked' : ''; ?> required>
                                                <label for="genero">Feminino</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label>Tamanho</label>
                                        <div class="cargo-options d-flex">
                                            <div class="me-3">
                                                <input type="radio" id="p" name="tamanho" value="P" <?= $produto['tamanho'] === 'P' ? 'checked' : ''; ?> required>
                                                <label for="gerenpte">P</label>
                                            </div>
                                            <div class="me-3">
                                                <input type="radio" id="m" name="tamanho" value="M" <?= $produto['tamanho'] === 'M' ? 'checked' : ''; ?> required>
                                                <label for="m">M</label>
                                            </div>
                                            <div class="me-3">
                                                <input type="radio" id="g" name="tamanho" value="G" <?= $produto['tamanho'] === 'G' ? 'checked' : ''; ?> required>
                                                <label for="g">G</label>
                                            </div>
                                            <div class="me-3">
                                                <input type="radio" id="gg" name="tamanho" value="GG" <?= $produto['tamanho'] === 'GG' ? 'checked' : ''; ?> required>
                                                <label for="gg">GG</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="quantidade">Quantidade</label>
                                        <input type="number" min="0" max="100" id="quantidade" name="quantidade" value="<?= $produto['quantidade'] ?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="preco">Preço</label>
                                        <input type="number" id="preco" min="0" step="0.01" name="preco" class="form-control" value="<?= $produto['preco'] ?>" placeholder="0.00" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Fornecedor</label>
                                        <select id="fornecedor" name="fornecedor" class="form-control" required>
                                            <option value="" disabled>Selecione um fornecedor</option>
                                            <?php
                                            // Realizando uma consulta na tabela fornecedores para alimentar a caixa de seleção
                                            $sql = "SELECT id, nome FROM fornecedores";
                                            $result = $conexao->query($sql);

                                            // Verificar se existem resultados
                                            if ($result && $result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    // Verificar se este é o fornecedor selecionado
                                                    $selected = ($row['id'] == $produto['fornecedor_id']) ? 'selected' : '';
                                                    echo '<option value="' . htmlspecialchars($row["id"]) . '" ' . $selected . '>' . htmlspecialchars($row["nome"]) . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">Nenhum fornecedor encontrado</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3 btn-center">
                                        <!--  Atribuição da função update_produto da classe (acoes) no botão de editar -->
                                        <button type="submit" name="update_produto" class="btn btn-primary">Editar</button>
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