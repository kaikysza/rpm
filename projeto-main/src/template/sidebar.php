<?php
//Importando arquivos de conexao e classe de funcionario
include_once '../classe/conexao.php';
include_once '../classe/class-funcionario.php';

//Iniciando a sessão
session_start();

// Armazene o cargo do funcionario
$cargo = $_SESSION['user']->cargo;

// Função para verificar permissões de acordo com o cargo
function checkAccess($allowedRoles)
{
  $userRole = $_SESSION['user']->cargo;
  if (!in_array($userRole, $allowedRoles)) {
    header("Location: ../pages/acesso-negado.php"); // Redirecionar para uma página de acesso negado
    exit();
  }
}

$pages = basename($_SERVER['PHP_SELF']); // Pega o nome da página atual

//Realiza verificação de acesso a página de acordo com o cargo registrado
switch ($pages) {
  case 'vendas.php':
  case 'cliente.php':
    checkAccess(['Vendedor', 'Caixa', 'Gerente']);
    break;

  case 'produto.php':
  case 'estoque.php':
    checkAccess(['Estoquista', 'Gerente']);
    break;

  case 'dashboard.php':
  case 'fornecedor.php':
  case 'funcionario.php':
  case 'relatorios.php':
    checkAccess(['Gerente']);
    break;
}
?>

<!DOCTYPE html>
<html lang="pt-br" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>RPM WEAR | Menu</title>
  <!--  ICONES BOOTSTRAP -->
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <!--  CSS -->
  <link rel="stylesheet" href="../src/css/sidebar.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
  <div class="sidebar">
    <div class="logo-details">
      <img src="../src/imagens/logo2.png" class="icon">
      <div class="logo_name">RPMWear</div>
      <i class='bx bx-menu' id="btn"></i>
    </div>
    <ul class="nav-list">
      <li>
        <a href="inicio.php">
          <i class='bx bx-home'></i>
          <span class="links_name">Inicio</span>
        </a>
        <span class="tooltip">Inicio</span>
      </li>
      <!--  Acesso liberado para VENDEDOR, CAIXA e GERENTE -->
      <?php if ($cargo === 'Vendedor' || $cargo === 'Caixa' || $cargo === 'Gerente'): ?>
        <li>
          <a href="venda.php">
            <i class='bx bx-cart-alt'></i>
            <span class="links_name">Vendas</span>
          </a>
          <span class="tooltip">Vendas</span>
        </li>
        <li>
          <a href="cliente.php">
            <i class='bx bx-male'></i>
            <span class="links_name">Clientes</span>
          </a>
          <span class="tooltip">Clientes</span>
        </li>
      <?php endif; ?>

      <!--  Acesso liberado para ESTOQUISTA e GERENTE -->
      <?php if ($cargo === 'Estoquista' || $cargo === 'Gerente'): ?>
        <li>
          <a href="produto.php">
            <i class='bx bx-shopping-bag'></i>
            <span class="links_name">Produtos</span>
          </a>
          <span class="tooltip">Produtos</span>
        </li>
        <li>
          <a href="estoque.php">
            <i class='bx bx-box'></i>
            <span class="links_name">Estoque</span>
          </a>
          <span class="tooltip">Estoque</span>
        </li>
      <?php endif; ?>

      <!--  Acesso liberado somente para GERENTE -->
      <?php if ($cargo === 'Gerente'): ?>
        <li>
          <a href="fornecedor.php">
            <i class='bx bx-package'></i>
            <span class="links_name">Fornecedores</span>
          </a>
          <span class="tooltip">Fornecedores</span>
        </li>
        <li>
          <a href="funcionario.php">
            <i class='bx bx-user'></i>
            <span class="links_name">Funcionários</span>
          </a>
          <span class="tooltip">Funcionários</span>
        </li>
        <li>
          <a href="dashboard.php">
            <i class='bx bx-grid-alt'></i>
            <span class="links_name">Dashboard</span>
          </a>
          <span class="tooltip">Dashboard</span>
        </li>
        <li>
          <a href="relatorio.php">
            <i class='bx bx-task'></i>
            <span class="links_name">Relatórios</span>
          </a>
          <span class="tooltip">Relatórios</span>
        </li>
      <?php endif; ?>

      <li class="profile">
        <div class="profile-details">
          <?php if (!empty($_SESSION['user']->foto)): ?>
            <img src="../src/uploads/<?php echo $_SESSION['user']->foto ?>" alt="profileImg">
          <?php else: ?>
            <img src="../src/imagens/user.jpg" class="icon" alt="Imagem padrão">
          <?php endif; ?>
          <div class="name_job">
            <div class="name"><?php echo $_SESSION['user']->nome ?></div>
            <div class="job"><?php echo $_SESSION['user']->cargo ?></div>
          </div>
        </div>
        <i class='bx bx-log-out' id="log_out" onclick="window.location.href='../classe/acoes.php?action=logout'"></i>
      </li>
    </ul>
  </div>
  <script src="../src/javascript/sidebar.js"></script>


</body>

</html>