<?php
include("menu.php");
include("conexao.php"); 


if (!isset($_SESSION['tipo'])) {
    header("Location: login.php");
    exit;
}

$tipo_usuario = $_SESSION['tipo'];

if ($tipo_usuario === 'paciente') {
    $id_usuario = $_SESSION['paciente_id'] ?? null;
} elseif ($tipo_usuario === 'medico') {
    $id_usuario = $_SESSION['medico_id'] ?? null;
} else {
    die("Acesso não autorizado.");
}

if (!$id_usuario) {
    die("Erro: ID do usuário não encontrado na sessão.");
}

if ($tipo_usuario === 'paciente') {
    $sql = "SELECT a.id, a.dia, a.hora, a.status,
                   c.nome AS clinica_nome, c.Cidade, c.estado,
                   m.nome AS medico_nome, m.especializacao AS medico_especializacao,
                   d.id AS diagnostico_id
            FROM agendados a
            JOIN clin_med cm ON a.id_clin_med = cm.id
            JOIN clinicas_ c ON cm.id_clinica = c.id
            JOIN medico m ON cm.id_medicos = m.id
            LEFT JOIN diagnostico d ON a.id = d.id_agendados
            WHERE a.id_paciente = ? 
              AND a.status NOT IN ('excluida', 'negado')";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
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
  <title>Consultas Agendadas - Clinic Help</title>
</head>
<body>

<div class="container">
    <h2 style="text-align:center; margin-top:20px;">Consultas Agendadas</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="card">
            <div>
              <?php if ($tipo_usuario === 'paciente'): ?>
                <p><strong>Clínica:</strong> <?php echo htmlspecialchars($row['clinica_nome']); ?></p>
                <p><strong>Localização:</strong> <?php echo htmlspecialchars($row['Cidade'] . " - " . $row['estado']); ?></p>
                <p><strong>Médico:</strong> <?php echo htmlspecialchars($row['medico_nome']); ?></p>
                <p><strong>Especialização:</strong> <?php echo htmlspecialchars($row['medico_especializacao']); ?></p>
              <?php else: ?>
                <p><strong>Paciente:</strong> <?php echo htmlspecialchars($row['paciente_nome']); ?></p>
                <p><strong>Clínica:</strong> <?php echo htmlspecialchars($row['clinica_nome']); ?></p>
                <p><strong>Localização:</strong> <?php echo htmlspecialchars($row['Cidade'] . " - " . $row['estado']); ?></p>
              <?php endif; ?>

              <p><strong>Data:</strong> <?php echo date("d/m/Y", strtotime($row['dia'])); ?></p>
              <p><strong>Hora:</strong> <?php echo date("H:i", strtotime($row['hora'])); ?></p>
              <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status'] ?? 'pendente'); ?></p>
            </div>

            <div class="actions">
              <?php if ($tipo_usuario === 'paciente'): ?>

    <?php if ($row['status'] === 'confirmar'): ?>
        <button class="btn confirm" onclick="confirmarConsultaPA(<?php echo $row['id']; ?>)">Confirmar Presença</button>
    <?php endif; ?>

    <?php if ($row['status'] !== 'concluido'): ?>
        <button class="btn edit" onclick="editarConsulta(<?php echo $row['id']; ?>)">Editar</button>
        <button class="btn delete" onclick="cancelarConsulta(<?php echo $row['id']; ?>)">Cancelar</button>
    <?php endif; ?>

<?php endif; ?>


              <?php if (!empty($row['diagnostico_id'])): ?>
                <a href="baixar_diagnostico.php?id=<?php echo $row['diagnostico_id']; ?>" class="btn download">Baixar Diagnóstico</a>
              <?php endif; ?>
            </div>
          </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center;">Nenhuma consulta agendada encontrada.</p>
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

function editarConsulta(id) {
    window.location.href = "editar_consulta.php?id=" + id;
}

function cancelarConsulta(id) {
    if (confirm("Deseja realmente cancelar esta consulta?")) {
        window.location.href = "cancelar_consulta.php?id=" + id;
    }
}

function confirmarConsultaPA(id) {
    if (confirm("Deseja confirmar sua presença nesta consulta?")) {
        window.location.href = "confirmar_consultaPA.php?id=" + id;
    }
}
</script>

</body>
</html>
