<?php
include_once '../classe/conexao.php';
date_default_timezone_set('America/Sao_Paulo');
$dataAtual = date('d/m/Y');
?>

<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RPM Wear | Menu Inicial</title>
  <!-- BOOTSTRAP -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- ICONES BOOTSTRAP -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../src/css/inicio.css">
</head>

<body>
  <?php include('../src/template/sidebar.php'); ?>

  <section class="home-section">
    <section class="main-container">
      <div class="profile-logo">
        <img src="../src/imagens/logo.png" alt="Logo" class="logo">
      </div>

      <div class="welcome-message">
        Bom te ver de volta, <?php echo htmlspecialchars($_SESSION['user']->nome); ?>!
      </div>
      <div class="current-date">
        Hoje é <?php echo date("d/m/Y"); ?>.
      </div>

      <div class="profile-detail">
        <?php if (!empty($_SESSION['user']->foto)): ?>
          <img src="../src/uploads/<?php echo $_SESSION['user']->foto ?>" alt="Imagem de perfil" class="profile-img">
        <?php else: ?>
          <img src="../src/imagens/user.jpg" alt="Imagem padrão" class="profile-img icon">
        <?php endif; ?>
        <div class="name_job">
          <div class="name"><?php echo htmlspecialchars($_SESSION['user']->nome); ?></div>
          <div class="job"><?php echo htmlspecialchars($_SESSION['user']->cargo); ?></div>
        </div>
      </div>
      <?php include('../src/template/footer.php'); ?>
    </section>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>