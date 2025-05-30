<?php
include_once '../classe/conexao.php';

// Total de clientes registrados
$sqlClientes = "SELECT COUNT(*) AS total_clientes FROM clientes";
$resultClientes = mysqli_query($conexao, $sqlClientes);
$total_clientes = $resultClientes ? mysqli_fetch_assoc($resultClientes)['total_clientes'] : 0;

// Total de produtos vendidos (considerando o total de vendas realizadas)
$sqlProdutosVendidos = "SELECT SUM(quantidade) AS total_vendidos FROM itens";
$resultProdutosVendidos = mysqli_query($conexao, $sqlProdutosVendidos);
$total_produtos_vendidos = $resultProdutosVendidos ? mysqli_fetch_assoc($resultProdutosVendidos)['total_vendidos'] : 0;

// Total vendido em $$ (soma de todos os valores das vendas)
$sqlTotalVendido = "SELECT SUM(preco_venda * quantidade) AS total_vendido FROM itens INNER JOIN vendas ON itens.venda_id = vendas.id";
$resultTotalVendido = mysqli_query($conexao, $sqlTotalVendido);
$total_vendido = $resultTotalVendido ? mysqli_fetch_assoc($resultTotalVendido)['total_vendido'] : 0;

// Últimas 10 vendas realizadas
$sqlUltimasCompras = "SELECT clientes.nome AS cliente, vendas.data_venda, SUM(itens.preco_venda * itens.quantidade) AS total_compra 
                      FROM vendas
                      INNER JOIN clientes ON vendas.cliente_id = clientes.id
                      INNER JOIN itens ON vendas.id = itens.venda_id
                      GROUP BY vendas.id ORDER BY vendas.data_venda DESC LIMIT 5";
$resultUltimasCompras = mysqli_query($conexao, $sqlUltimasCompras);

// Ranking top 10 produtos que foram mais vendidos
$sqlRankingProdutos = "SELECT produtos.nome AS produto, produtos.imagem AS imagem, SUM(itens.preco_venda * itens.quantidade) AS total_vendas
					   FROM vendas
					   INNER JOIN itens ON vendas.id = itens.venda_id
					   INNER JOIN produtos ON itens.produto_id = produtos.id
					   GROUP BY produtos.id ORDER BY total_vendas DESC LIMIT 3";

$resultRankingProdutos = mysqli_query($conexao, $sqlRankingProdutos);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>RPM Wear | Dashboard </title>
	<!--  BOOTSTRAP -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<!--  ICONES BOOTSTRAP -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="../src/css/dashboard.css">
</head>

<body>
	<!--  Importação da Sidebar -->
	<?php include('../src/template/sidebar.php'); ?>

	<section class="home-section">
		<section id="content">
			<main>
				<div class="head-title">
					<div class="left">
						<h1>RPM WEAR | Dashboard</h1>
					</div>
				</div>

				<ul class="box-info">
					<li>
						<i class='bx bx-store-alt'></i>
						<span class="text">
							<p>Produtos vendidos</p>
							<h3><?php echo $total_produtos_vendidos; ?></h3>
						</span>
					</li>
					<li>
						<i class='bx bxs-group'></i>
						<span class="text">
							<p>Clientes</p>
							<h3><?php echo $total_clientes; ?></h3>
						</span>
					</li>
					<li>
						<i class='bx bxs-dollar-circle'></i>
						<span class="text">
							<p>Total Vendido</p>
							<h3>R$ <?php echo number_format($total_vendido, 2, ',', '.'); ?></h3>
						</span>
					</li>
				</ul>

				<div class="table-data">
					<div class="order">
						<div class="head">
							<h3>Vendas Recentes</h3>
						</div>
						<table>
							<thead>
								<tr>
									<th>Cliente</th>
									<th>Data da Compra</th>
									<th>Valor</th>
								</tr>
							</thead>
							<tbody>
								<?php while ($row = mysqli_fetch_assoc($resultUltimasCompras)) { ?>
									<tr>
										<td>
											<p><?php echo $row['cliente']; ?></p>
										</td>
										<td><?php echo date('d/m/Y', strtotime($row['data_venda'])); ?></td>
										<td><span>R$ <?php echo number_format($row['total_compra'], 2, ',', '.'); ?></span></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>

					<!-- Ranking Produtos -->
					<div class="todo">
						<div class="head">
							<h3>Produtos mais vendidos</h3>
						</div>
						<ul class="todo-list">
							<?php
							$posicao = 1;
							while ($row = mysqli_fetch_assoc($resultRankingProdutos)) { ?>
								<li>
									<img src="../src/<?php echo $row['imagem']; ?>" alt="<?php echo $row['produto']; ?>" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
									<p><?php echo $posicao . "º " . $row['produto']; ?> - R$ <?php echo number_format($row['total_vendas'], 2, ',', '.'); ?></p>
								</li>
							<?php
								$posicao++;
							} ?>
						</ul>
					</div>
				</div>
			</main>
		</section>
		<?php include('graficos.php'); ?>
	</section>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>