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

$sql = "SELECT a.id, a.dia, a.hora, a.status,
               p.Nome_paciente AS paciente_nome, p.telefone, p.email,
               c.nome AS clinica_nome
        FROM agendados a
        JOIN pacientes p ON a.id_paciente = p.id
        JOIN clin_med cm ON a.id_clin_med = cm.id
        JOIN clinicas_ c ON cm.id_clinica = c.id
        WHERE cm.id_medicos = ? 
          AND cm.situacao = 1
          AND a.id_paciente != 0
          AND a.status NOT IN ('negado', 'excluida', 'concluido')"; 
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erro ao preparar a query: " . $conn->error);
}
$stmt->bind_param("i", $id_medico);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/agendados.css">
  <link rel="icon" href="../image/clinichelpicon.png">
  <title>Consultas Agendadas - Médico</title>
</head>
<body>

<div class="container">
  <h2 style="text-align:center; margin-top:20px;">Consultas Agendadas</h2>

  <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
          <div>
              <p><strong>Paciente:</strong> <?php echo htmlspecialchars($row['paciente_nome']); ?></p>
              <p><strong>Telefone:</strong> <?php echo htmlspecialchars($row['telefone']); ?></p>
              <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
              <p><strong>Clínica:</strong> <?php echo htmlspecialchars($row['clinica_nome']); ?></p>
              <p><strong>Data:</strong> <?php echo date("d/m/Y", strtotime($row['dia'])); ?></p>
              <p><strong>Hora:</strong> <?php echo date("H:i", strtotime($row['hora'])); ?></p>
              <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status'] ?? 'pendente'); ?></p>
          </div>

          <div class="actions">
            <a href="enviar_diagnostico.php?id=<?php echo $row['id']; ?>" class="btn_upload">Enviar Diagnóstico</a>
          </div>
        </div>
      <?php endwhile; ?>
  <?php else: ?>
      <p style="text-align:center;">Nenhuma consulta encontrada.</p>
  <?php endif; ?>
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
