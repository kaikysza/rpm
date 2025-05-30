<?php
include_once '../classe/conexao.php';

// Consulta SQL para obter os dados de vendas por mês
$query = "SELECT DATE_FORMAT(v.data_venda, '%Y-%m') AS ano_mes,
          MONTH(v.data_venda) AS mes_numero,
          SUM(i.preco_venda * i.quantidade) AS total_vendas
          FROM vendas v
          JOIN itens i ON v.id = i.venda_id
          GROUP BY DATE_FORMAT(v.data_venda, '%Y-%m')
          ORDER BY ano_mes ASC";

$result = $conexao->query($query);

// Preparar os dados para o gráfico de vendas por mês
$chartData = [["Mês", "Vendas"]];
$meses = [
    1 => "Janeiro",
    2 => "Fevereiro",
    3 => "Março",
    4 => "Abril",
    5 => "Maio",
    6 => "Junho",
    7 => "Julho",
    8 => "Agosto",
    9 => "Setembro",
    10 => "Outubro",
    11 => "Novembro",
    12 => "Dezembro"
];

while ($row = $result->fetch_assoc()) {
    // Mapeia o número do mês para o nome do mês
    $mes = $meses[(int)$row['mes_numero']];
    $chartData[] = [$mes, (float)$row['total_vendas']];
}

// Consulta SQL para contar os produtos masculinos e femininos
$query = "SELECT SUM(CASE WHEN genero = 'Masculino' THEN quantidade ELSE 0 END) AS masculino,
          SUM(CASE WHEN genero = 'Feminino' THEN quantidade ELSE 0 END) AS feminino
          FROM produtos";

$result = $conexao->query($query);
$row = $result->fetch_assoc();
$masculino = (int)$row['masculino'];
$feminino = (int)$row['feminino'];

// Preparar os dados para o gráfico de pizza (Masculino vs Feminino)
$chartDataPizza = [
    ['Genero', 'Quantidade'],
    ['Masculino', $masculino],
    ['Feminino', $feminino]
];

// Consulta SQL para os produtos mais vendidos por funcionário (com total de vendas em R$)
$query = "SELECT f.nome AS funcionario, 
                 DATE_FORMAT(v.data_venda, '%Y-%m') AS ano_mes, 
                 SUM(i.preco_venda * i.quantidade) AS total_vendido
          FROM vendas v
          JOIN itens i ON v.id = i.venda_id
          JOIN funcionarios f ON v.funcionario_id = f.id
          GROUP BY f.nome, ano_mes
          ORDER BY ano_mes ASC, total_vendido DESC";

$result = $conexao->query($query);

// Organizando os dados para mostrar os 3 melhores vendedores por mês (em R$)
$chartDataFuncionario = [["Mês", "Funcionário 1", "Funcionário 2", "Funcionário 3"]];
$funcionarios_por_mes = [];

while ($row = $result->fetch_assoc()) {
    $ano_mes = $row['ano_mes'];
    if (!isset($funcionarios_por_mes[$ano_mes])) {
        $funcionarios_por_mes[$ano_mes] = [];
    }

    // Armazenar os 3 melhores vendedores com o valor total vendido em R$
    $funcionarios_por_mes[$ano_mes][] = [
        'funcionario' => $row['funcionario'],
        'total_vendido' => (float)$row['total_vendido']
    ];
}

// Preenchendo os dados para o gráfico, garantindo que apenas os 3 primeiros sejam usados por mês
foreach ($funcionarios_por_mes as $ano_mes => $funcionarios) {
    $top_3 = array_slice($funcionarios, 0, 3);  // Pega os 3 primeiros funcionários
    $data_row = [$meses[(int)substr($ano_mes, 5, 2)]]; // Exibe o mês por extenso (sem o ano)

    // Preenche as colunas para os 3 funcionários, agora com os valores em R$
    for ($i = 0; $i < 3; $i++) {
        if (isset($top_3[$i])) {
            $data_row[] = $top_3[$i]['funcionario'] . " (R$ " . number_format($top_3[$i]['total_vendido'], 2, ',', '.') . ")";
        } else {
            $data_row[] = "N/A";  // Caso não haja 3 funcionários para algum mês
        }
    }

    $chartDataFuncionario[] = $data_row;
}

// Consulta SQL para os produtos mais vendidos
$query = "SELECT p.nome AS produto, SUM(i.quantidade) AS total_vendido,
          DATE_FORMAT(v.data_venda, '%Y-%m') AS ano_mes
          FROM itens i
          JOIN produtos p ON i.produto_id = p.id
          JOIN vendas v ON v.id = i.venda_id
          GROUP BY p.nome, ano_mes
          ORDER BY ano_mes ASC, total_vendido DESC";

$result = $conexao->query($query);
$chartDataProdutos = [["Mês", "Produto 1", "Produto 2", "Produto 3"]];
$produtos_por_mes = [];

while ($row = $result->fetch_assoc()) {
    $ano_mes = $row['ano_mes'];
    if (!isset($produtos_por_mes[$ano_mes])) {
        $produtos_por_mes[$ano_mes] = [];
    }

    // Adiciona o produto à lista de produtos vendidos por mês
    $produtos_por_mes[$ano_mes][] = [
        'produto' => $row['produto'],
        'total_vendido' => (int)$row['total_vendido']
    ];
}

// Preenche os dados para o gráfico de produtos mais vendidos por mês
foreach ($produtos_por_mes as $ano_mes => $produtos) {
    $top_3 = array_slice($produtos, 0, 3);  // Pega os 3 primeiros produtos
    $data_row = [$meses[(int)substr($ano_mes, 5, 2)]]; // Exibe o mês por extenso (sem o ano)

    // Preenche as colunas para os 3 produtos
    for ($i = 0; $i < 3; $i++) {
        if (isset($top_3[$i])) {
            $data_row[] = $top_3[$i]['produto'] . " (" . $top_3[$i]['total_vendido'] . " unidades)";
        } else {
            $data_row[] = "N/A";  // Caso não haja 3 produtos para algum mês
        }
    }

    $chartDataProdutos[] = $data_row;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios de Vendas</title>
    <link rel="stylesheet" href="../src/css/grafico.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Gráfico de Vendas por Mês (Gráfico de Linhas)
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawSalesChart);

        function drawSalesChart() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($chartData); ?>);
            var options = {
                title: 'Desempenho de Vendas por Mês',
                curveType: 'function',
                legend: {
                    position: 'bottom'
                },
                backgroundColor: '#f7f9fc',
                colors: ['#1e88e5'],
                titleTextStyle: {
                    color: '#1e88e5',
                    fontSize: 18,
                    bold: true
                },
                hAxis: {
                    textStyle: {
                        color: '#333'
                    }
                },
                vAxis: {
                    textStyle: {
                        color: '#333'
                    }
                }
            };
            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
            chart.draw(data, options);
        }

        // Gráfico de Produtos Vendidos (Masculino X Feminino - Gráfico de Pizza)
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawGenderChart);

        function drawGenderChart() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($chartDataPizza); ?>);
            var options = {
                title: 'Produtos Vendidos: Masculino vs Feminino',
                backgroundColor: '#f7f9fc',
                colors: ['#1e88e5', '#ff7043'],
                titleTextStyle: {
                    color: '#1e88e5',
                    fontSize: 18,
                    bold: true
                }
            };
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }

        // Gráfico de Produtos Vendidos por Funcionário
        google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawEmployeeSalesChart);

        function drawEmployeeSalesChart() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($chartDataFuncionario); ?>);
            var options = {
                chart: {
                    title: 'Top 3 Funcionários com Mais Vendas por Mês'
                },
                bars: 'vertical',
                colors: ['#4caf50', '#ffa726', '#d32f2f'],
                backgroundColor: '#f7f9fc',
                legend: {
                    position: 'top',
                    textStyle: {
                        fontSize: 12
                    }
                }
            };

            var chart = new google.charts.Bar(document.getElementById('employee_sales_chart'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }

        // Gráfico de Produtos Mais Vendidos
        google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawTopProductsChart);

        function drawTopProductsChart() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($chartDataProdutos); ?>);
            var options = {
                chart: {
                    title: 'Produtos Mais Vendidos Mensalmente'
                },
                bars: 'horizontal',
                colors: ['#2196f3', '#4caf50', '#ff9800'],
                backgroundColor: '#f7f9fc',
                legend: {
                    position: 'top',
                    textStyle: {
                        fontSize: 12
                    }
                }
            };
            var chart = new google.charts.Bar(document.getElementById('barchart_material'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
    </script>
</head>

<body>

    <!-- Container para a primeira fileira -->
    <div class="chart-container">
        <div class="chart-box" id="curve_chart"></div>
        <div class="chart-box" id="piechart"></div>
    </div>

    <!-- Container para a segunda fileira -->
    <div class="chart-container">
        <div class="chart-box" id="employee_sales_chart"></div>
        <div class="chart-box" id="barchart_material"></div>
    </div>
</body>

</html>