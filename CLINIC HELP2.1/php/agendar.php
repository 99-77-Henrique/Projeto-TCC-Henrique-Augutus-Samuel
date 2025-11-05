<!DOCTYPE html>
<html lang="pt-br">
<head>
<?php
// INCLUDEs
include('conexao.php');
include('menu.php');

// ID do paciente logado
$id_paciente = $_SESSION["paciente_id"] ?? null;

// ID da clínica vindo do botão de agendamento via GET
$id_clinica = $_GET['id_clin'] ?? 0;

// Buscando o nome da clínica para exibir
$clinica_nome = "Clínica inválida";
if ($id_clinica && isset($conn)) {
    $sql_clinica = "SELECT nome FROM clinicas_ WHERE id = ?";
    $stmt2 = $conn->prepare($sql_clinica);
    $stmt2->bind_param("i", $id_clinica);
    $stmt2->execute();
    $res_clin = $stmt2->get_result();
    if ($res_clin && $res_clin->num_rows > 0) {
        $clinica_nome = $res_clin->fetch_assoc()['nome'];
    }
}
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Agendar Consulta - Clinic Help</title>
<link rel="stylesheet" href="../css/agendar.css">
<link rel="icon" href="../image/clinichelpicon.png">
</head>
<body>

<main class="agendamento">
<h2>Agendar Consulta</h2>

<form action="agenda.php" method="POST" class="form-agendar">
    <!-- Campos ocultos -->
    <input type="hidden" name="id_pacientes" value="<?php echo htmlspecialchars($id_paciente); ?>">
    <input type="hidden" name="id_clin_med" value="<?php echo htmlspecialchars($id_clinica); ?>">

    <!-- Exibir nome da clínica -->
    <p><strong>na</strong> <?php echo htmlspecialchars($clinica_nome); ?></p>

    <!-- Select de médicos -->
    <label for="clinica">Médico:</label>
    <select id="clinica" name="id_clin_med" required>
  <option value="">Selecione</option>
  <?php
  if ($id_clinica && isset($conn)) {
      $sql_medicos = "
          SELECT cm.id AS clin_med_id,
                 m.nome,
                 m.especializacao
          FROM medico m
          INNER JOIN clin_med cm ON cm.id_medicos = m.id
          WHERE cm.id_clinica = ?
          ORDER BY m.nome ASC
      ";
      $stmt = $conn->prepare($sql_medicos);
      $stmt->bind_param("i", $id_clinica);
      $stmt->execute();
      $result_medicos = $stmt->get_result();

      if ($result_medicos && $result_medicos->num_rows > 0) {
          while ($med = $result_medicos->fetch_assoc()) {
              echo '<option value="' . htmlspecialchars($med['clin_med_id']) . '">'
                   . htmlspecialchars($med['nome'])
                   . ' — ' . htmlspecialchars($med['especializacao'])
                   . '</option>';
          }
      } else {
          echo '<option value="">Nenhum médico vinculado a esta clínica</option>';
      }
  } else {
      echo '<option value="">Nenhum médico disponível</option>';
  }
  ?>
</select>



    <!-- Data da consulta -->
    <label for="data">Data da consulta:</label>
    <input type="date" id="data" name="data" required>

    <!-- Horário da consulta -->
    <label for="hora">Horário:</label>
    <input type="time" id="hora" name="hora" required>

    <!-- Botão -->
    <button type="submit">Confirmar Agendamento</button>
</form>
</main>

<!-- Script para limitar datas e horários -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputData = document.getElementById('data');
    const inputHora = document.getElementById('hora');

    const hoje = new Date();
    const ano = hoje.getFullYear();
    const mes = String(hoje.getMonth() + 1).padStart(2, '0');
    const dia = String(hoje.getDate()).padStart(2, '0');
    inputData.min = `${ano}-${mes}-${dia}`;

    function atualizarHorarioMinimo() {
        if (!inputData.value) {
            inputHora.removeAttribute('min');
            return;
        }
        const hojeStr = hoje.toISOString().split('T')[0];
        if (inputData.value === hojeStr) {
            const horas = String(hoje.getHours()).padStart(2, '0');
            const minutos = String(hoje.getMinutes()).padStart(2, '0');
            inputHora.min = `${horas}:${minutos}`;
        } else {
            inputHora.removeAttribute('min');
        }
    }

    inputData.addEventListener('change', atualizarHorarioMinimo);
    atualizarHorarioMinimo();
});
</script>

</body>
</html>
