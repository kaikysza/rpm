<?php
include_once '../../classe/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RPM Wear | Cadastrar Produto</title>
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
                        <h4>Cadastrar produto
                            <!--  Botão para voltar a página de produtos cadastrados -->
                            <a href="../produto.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <!--  Criação do formulário com a classe (acoes) -->
                        <form action="../../classe/acoes.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="foto">Carregar Foto</label>
                                <input type="file" id="foto" name="foto" class="form-control" accept="image/*" required>
                            </div>
                            <div class="mb-3">
                                <label for="nome">Nome</label>
                                <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite o nome do produto" required>
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
                                        <input type="radio" id="masculino" name="genero" value="Masculino" required>
                                        <label for="genero">Masculino</label>
                                    </div>
                                    <div class="me-3">
                                        <input type="radio" id="feminino" name="genero" value="Feminino" required>
                                        <label for="genero">Feminino</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Tamanho</label>
                                <div class="cargo-options d-flex">
                                    <div class="me-3">
                                        <input type="radio" id="p" name="tamanho" value="P" required>
                                        <label for="gerenpte">P</label>
                                    </div>
                                    <div class="me-3">
                                        <input type="radio" id="m" name="tamanho" value="M" required>
                                        <label for="m">M</label>
                                    </div>
                                    <div class="me-3">
                                        <input type="radio" id="g" name="tamanho" value="G" required>
                                        <label for="g">G</label>
                                    </div>
                                    <div class="me-3">
                                        <input type="radio" id="gg" name="tamanho" value="GG" required>
                                        <label for="gg">GG</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="quantidade">Quantidade</label>
                                <input type="number" min="0" max="100" id="quantidade" name="quantidade" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="preco">Preço</label>
                                <input type="number" id="preco" min="0" step="0.01" name="preco" class="form-control" placeholder="0.00" required>
                            </div>
                            <div class="mb-3">
                                <label>Fornecedor</label>
                                <select id="fornecedor" name="fornecedor" class="form-control" required>
                                    <option value="" disabled selected>Selecione um fornecedor</option>
                                    <?php
                                    // Realizando uma consulta na tabela fornecedores para alimentar a caixa de seleção
                                    $sql = "SELECT id, nome FROM fornecedores";
                                    $result = $conexao->query($sql);

                                    // Verificar se existem resultados
                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<option value="' . htmlspecialchars($row["id"]) . '">' . htmlspecialchars($row["nome"]) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">Nenhum fornecedor encontrado</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3 btn-center">
                                <!--  Atribuição da função create_produto da classe (acoes) no botão de cadastrar -->
                                <button type="submit" name="create_produto" class="btn btn-primary">Cadastrar</button>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>