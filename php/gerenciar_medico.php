<?php
include("menu.php");
include("conexao.php");

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'clinica') {
    header("Location: login.php");
    exit;
}

$id_clinica = $_SESSION['clinica_id'] ?? null;
if (!$id_clinica) {
    die("Erro: ID da clínica não encontrado na sessão.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Gerenciar Médicos - Clinic Help</title>
  <link rel="icon" href="../image/clinichelpicon.png">
  <link rel="stylesheet" href="../css/gerenciar_medico.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="container">
  <h2>Gerenciar Médicos da Clínica</h2>

  <a href="clinicachamamarrmedico.php" class="btn contratar">Contratar Médico</a>
  <a href="sair_medico.php" class="btn retirar">Retirar Médico</a>

  <p style="margin-top: 20px; color:#666;">Escolha uma das opções acima para gerenciar os médicos associados à sua clínica.</p>
</div>

<footer style="text-align:center; margin-top:50px;">
  <p>&#169; 2025 CLINIC HELP. Todos os direitos reservados.</p>
</footer>

<div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
  </div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
  </script>

</body>
</html>
