<?php
include_once '../classe/conexao.php';

$sqlProdutosVendidos = "SELECT p.nome AS produto, p.cor, p.tamanho, p.genero,
                        SUM(i.quantidade) AS total_vendido,
                        SUM(i.quantidade * i.preco_venda) AS valor_total
                        FROM itens i
                        JOIN produtos p ON i.produto_id = p.id 
                        GROUP BY p.nome, p.cor, p.tamanho, p.genero
                        ORDER BY total_vendido DESC;
";

$sqlVendasRealizadas = "SELECT v.id AS venda_id, v.data_venda, c.nome AS cliente_nome, 
                        SUM(i.preco_venda * i.quantidade) AS total_venda, f.nome AS vendedor
                        FROM vendas v 
                        JOIN clientes c ON v.cliente_id = c.id
                        JOIN itens i ON v.id = i.venda_id
                        JOIN funcionarios f ON v.funcionario_id = f.id
                        GROUP BY v.id, v.data_venda, c.nome, f.nome
                        ORDER BY v.data_venda DESC;
";

$sqlStatusEstoque = "SELECT p.nome AS produto, p.genero, p.tamanho, 
                     SUM(p.quantidade) AS quantidade_estoque
                     FROM produtos p
                     GROUP BY p.nome, p.genero, p.tamanho
                     ORDER BY quantidade_estoque ASC;
";

$sqlVendasFuncionarios = "SELECT f.nome AS funcionario, COUNT(v.id) AS quantidade_vendas,
                          SUM(i.preco_venda * i.quantidade) AS total_vendas
                          FROM vendas v
                          JOIN funcionarios f ON v.funcionario_id = f.id
                          JOIN itens i ON v.id = i.venda_id
                          GROUP BY f.nome
                          ORDER BY total_vendas DESC;
";

$sqlClientesCadastrados = "SELECT nome AS cliente, cpf, telefone FROM clientes ORDER BY nome ASC;";

$sqlFornecedoresCadastrados = "SELECT nome AS fornecedor, cnpj, telefone, descricao, status FROM fornecedores ORDER BY nome ASC;
";

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RPM Wear | Relatórios</title>
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- ICONES BOOTSTRAP -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../src/css/relatorio.css">
    <!-- HTML2PDF  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- GOOGLE CHARTS -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<body>
    <?php include('../src/template/sidebar.php'); ?>
    <section class="home-section">
        <header class="p-3 text-white text-center">
            <h1>Painel de Relatórios e Desempenho</h1>
        </header>
        <section class="main-container container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="report-card">
                        <label>Produtos Mais Vendidos</label>
                        <a href="#" class="btn-download" onclick="gerarPDF('produtos-vendidos')">
                            <i class='bx bxs-cloud-download'></i>
                            <span class="text">Gerar PDF</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="report-card">
                        <label>Vendas Realizadas</label>
                        <a href="#" class="btn-download" onclick="gerarPDF('vendas-realizadas')">
                            <i class='bx bxs-cloud-download'></i>
                            <span class="text">Gerar PDF</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="report-card">
                        <label>Status do Estoque</label>
                        <a href="#" class="btn-download" onclick="gerarPDF('status-estoque')">
                            <i class='bx bxs-cloud-download'></i>
                            <span class="text">Gerar PDF</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="report-card">
                        <label>Vendas por Funcionário</label>
                        <a href="#" class="btn-download" onclick="gerarPDF('vendas-funcionarios')">
                            <i class='bx bxs-cloud-download'></i>
                            <span class="text">Gerar PDF</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="report-card">
                        <label>Clientes Cadastrados</label>
                        <a href="#" class="btn-download" onclick="gerarPDF('clientes-cadastrados')">
                            <i class='bx bxs-cloud-download'></i>
                            <span class="text">Gerar PDF</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="report-card">
                        <label>Fornecedores Cadastrados</label>
                        <a href="#" class="btn-download" onclick="gerarPDF('fornecedores-cadastrados')">
                            <i class='bx bxs-cloud-download'></i>
                            <span class="text">Gerar PDF</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <?php include('../src/template/footer.php'); ?>
    </section>

    <!-- Relatório de Produtos Mais Vendidos -->
    <div id="produtos-vendidos" style="display:none;">
        <h3>Produtos Mais Vendidos</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Cor</th>
                    <th>Tamanho</th>
                    <th>Gênero</th>
                    <th>Total</th>
                    <th>Total (R$)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conexao, $sqlProdutosVendidos);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>" . $row['produto'] . "</td>
                        <td>" . $row['cor'] . "</td>
                        <td>" . $row['tamanho'] . "</td>
                        <td>" . $row['genero'] . "</td>
                        <td>" . $row['total_vendido'] . "</td>
                        <td>R$ " . number_format($row['valor_total'], 2, ',', '.') . "</td>
                      </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Relatório de Vendas Realizadas -->
    <div id="vendas-realizadas" style="display:none;">
        <h3>Vendas Realizadas</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th>Total (R$)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conexao, $sqlVendasRealizadas);
                while ($row = mysqli_fetch_assoc($result)) {
                    // Data original sem formatação
                    $dataVenda = $row['data_venda'];

                    echo "<tr>
                        <td>" . $row['venda_id'] . "</td>
                        <td>" . date('d/m/Y', strtotime($dataVenda)) . "</td> <!-- Formatação de Data no HTML -->
                        <td>" . $row['cliente_nome'] . "</td>
                        <td>" . $row['vendedor'] . "</td>
                        <td>R$ " . number_format($row['total_venda'], 2, ',', '.') . "</td>
                      </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Relatório de Status do Estoque -->
    <div id="status-estoque" style="display:none;">
        <h3>Status do Estoque</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Gênero</th>
                    <th>Tamanho</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conexao, $sqlStatusEstoque);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>" . $row['produto'] . "</td>
                        <td>" . $row['genero'] . "</td>
                        <td>" . $row['tamanho'] . "</td>
                        <td>" . $row['quantidade_estoque'] . "</td>
                      </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>


    <!-- Relatório de Vendas por Funcionário -->
    <div id="vendas-funcionarios" style="display:none;">
        <h3>Vendas por Funcionário</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Funcionário</th>
                    <th>Quantidade de Vendas</th>
                    <th>Total (R$)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conexao, $sqlVendasFuncionarios);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>" . $row['funcionario'] . "</td>
                        <td>" . $row['quantidade_vendas'] . "</td>
                        <td>R$ " . number_format($row['total_vendas'], 2, ',', '.') . "</td>
                      </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Relatório de Clientes Cadastrados -->
    <div id="clientes-cadastrados" style="display:none;">
        <h3>Clientes Cadastrados</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conexao, $sqlClientesCadastrados);
                while ($row = mysqli_fetch_assoc($result)) {
                    // Formatar o CPF
                    $cpf = $row['cpf'];
                    $cpfFormatado = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);

                    // Formatar o Telefone
                    $telefone = $row['telefone'];
                    $telefoneFormatado = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);

                    echo "<tr>
                        <td>" . $row['cliente'] . "</td>
                        <td>" . $cpfFormatado . "</td>
                        <td>" . $telefoneFormatado . "</td>
                      </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Relatório de Fornecedores Cadastrados -->
    <div id="fornecedores-cadastrados" style="display:none;">
        <h3>Fornecedores Cadastrados</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fornecedor</th>
                    <th>CNPJ</th>
                    <th>Telefone</th>
                    <th>Descrição</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conexao, $sqlFornecedoresCadastrados);
                while ($row = mysqli_fetch_assoc($result)) {
                    // Formatar o CNPJ
                    $cnpj = $row['cnpj'];
                    $cnpjFormatado = preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);

                    // Formatar o Telefone
                    $telefone = $row['telefone'];
                    $telefoneFormatado = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);

                    echo "<tr>
                        <td>" . $row['fornecedor'] . "</td>
                        <td>" . $cnpjFormatado . "</td>
                        <td>" . $telefoneFormatado . "</td>
                        <td>" . $row['descricao'] . "</td>
                        <td>" . $row['status'] . "</td>
                      </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>



    <script>
        function gerarPDF(relatorioId) {
            const element = document.getElementById(relatorioId);

            // Mostrar o elemento antes de gerar o PDF
            element.style.display = "block";

            var opt = {
                margin: 1,
                filename: relatorioId + ".pdf",
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: "in",
                    format: "letter",
                    orientation: "landscape"
                }
            };

            // Gerar o PDF e depois esconder o elemento novamente
            html2pdf().set(opt).from(element).save().then(() => {
                element.style.display = "none";
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>