<?php
include("menu.php");
include("conexao.php");


if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'medico') {
    header("Location: login.php");
    exit;
}

$id_medico = $_SESSION['medico_id'] ?? null;

if (!$id_medico) {
    die("Erro: ID do médico não encontrado na sessão.");
}


$sql = "SELECT cm.id AS id_relacao, c.nome, c.telefone, c.CNPJ, c.cidade, c.estado
        FROM clin_med cm
        JOIN clinicas_ c ON cm.id_clinica = c.id
        WHERE cm.id_medicos = ? AND cm.situacao = 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_medico);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Minhas Clínicas - Clinic Help</title>
  <link rel="stylesheet" href="../css/medico_clinicas.css">
  <link rel="icon" href="../image/clinichelpicon.png">
</head>
<body>
  <main class="container">
    <h2>Minhas Clínicas</h2>

    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
          <p><strong>Nome:</strong> <?= htmlspecialchars($row['nome']) ?></p>
          <p><strong>Cidade:</strong> <?= htmlspecialchars($row['cidade']) ?> - <?= htmlspecialchars($row['estado']) ?></p>
          <p><strong>Telefone:</strong> <?= htmlspecialchars($row['telefone']) ?></p>
          <p><strong>CNPJ:</strong> <?= htmlspecialchars($row['CNPJ']) ?></p>

          <form method="POST" action="sair_clinica.php" onsubmit="return confirm('Deseja realmente sair desta clínica?');">
            <input type="hidden" name="id_relacao" value="<?= $row['id_relacao'] ?>">
            <button type="submit" class="btn-sair">Sair da Clínica</button>
          </form>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>Você não está vinculado a nenhuma clínica.</p>
    <?php endif; ?>
  </main>

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
