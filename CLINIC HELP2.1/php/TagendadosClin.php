<?php
include("menu.php");
include("conexao.php"); 

// Verifica login e tipo
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'clinica') {
    header("Location: login.php");
    exit;
}

$id_clinica = $_SESSION['clinica_id'] ?? null;

if (!$id_clinica) {
    die("Erro: ID da clínica não encontrado na sessão.");
}

// Query para clínica: paciente, médico, data/hora, status
$sql = "SELECT a.id, a.dia, a.hora, a.status,
               p.Nome_paciente AS paciente_nome, p.telefone, p.email,
               m.nome AS medico_nome
        FROM agendados a
        JOIN pacientes p ON a.id_paciente = p.id
        JOIN clin_med cm ON a.id_clin_med = cm.id
        JOIN medico m ON cm.id_medicos = m.id
        WHERE cm.id_clinica = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_clinica);
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
  <title>Consultas Agendadas - Clínica</title>
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
                <p><strong>Médico:</strong> <?php echo htmlspecialchars($row['medico_nome']); ?></p>
                <p><strong>Data:</strong> <?php echo date("d/m/Y", strtotime($row['dia'])); ?></p>
                <p><strong>Hora:</strong> <?php echo date("H:i", strtotime($row['hora'])); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status'] ?? 'pendente'); ?></p>
            </div>

            <div class="actions">
                <button class="btn confirm" onclick="confirmarConsulta(<?php echo $row['id']; ?>)">Confirmar</button>
                <button class="btn delete" onclick="cancelarConsulta(<?php echo $row['id']; ?>)">Cancelar</button>
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

<script>
function cancelarConsulta(id) {
    if (confirm("Deseja realmente cancelar esta consulta?")) {
        window.location.href = "cancelar_consultaCl.php?id=" + id;
    }
}

function confirmarConsulta(id) {
    if (confirm("Deseja confirmar esta consulta?")) {
        window.location.href = "confirmar_consulta.php?id=" + id;
    }
}
</script>

</body>
</html>
