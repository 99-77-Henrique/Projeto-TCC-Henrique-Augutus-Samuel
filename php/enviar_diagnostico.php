<?php
include("menu.php");
include("conexao.php");

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'medico') {
    header("Location: login.php");
    exit;
}

$id_agendados = $_GET['id'] ?? null;

if (!$id_agendados) {
    die("<script>alert('Consulta não encontrada.'); window.location='TagendadosMed.php';</script>");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/agendados.css">
  <link rel="icon" href="../image/clinichelpicon.png">
  <title>Enviar Diagnóstico</title>
</head>
<body>

<div class="container">
  <h2 style="text-align:center; margin-top:20px;">Enviar Diagnóstico (PDF)</h2>

  <form action="processar_diagnostico.php" method="POST" enctype="multipart/form-data" style="max-width:500px; margin:auto;">
    <input type="hidden" name="id_agendados" value="<?php echo htmlspecialchars($id_agendados); ?>">
    <label for="documento">Selecione o arquivo PDF:</label>
    <input type="file" id="documento" name="documento" accept="application/pdf" required>
    <button type="submit" class="btn_upload" style="margin-top:15px;">Enviar Diagnóstico</button>
  </form>

  <div style="text-align:center; margin-top:20px;">
    <a href="TagendadosMed.php" class="btn_back">← Voltar</a>
  </div>
</div>

<footer>
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
