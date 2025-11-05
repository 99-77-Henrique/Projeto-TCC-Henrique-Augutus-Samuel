<?php
include("conexao.php");
include("menu.php");

// Verificar se usuário logado
if (!isset($_SESSION['tipo'])) {
    header("Location: login.php");
    exit;
}

$tipo_usuario = $_SESSION['tipo'];
$user_id = null;
if ($tipo_usuario === 'paciente') {
    $user_id = $_SESSION['paciente_id'] ?? null;
} elseif ($tipo_usuario === 'clinica') {
    $user_id = $_SESSION['clinica_id'] ?? null;
} else {
    die("Acesso inválido.");
}

if (!$user_id) {
    die("Usuário não identificado.");
}

// Receber o ID da consulta via GET
$consulta_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($consulta_id <= 0) {
    die("Consulta inválida.");
}

// Buscar os dados da consulta para preenchimento
$sql = "SELECT id_paciente, id_clin_med, dia, hora, status
        FROM agendados
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $consulta_id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result || $result->num_rows === 0) {
    die("Consulta não encontrada.");
}
$consulta = $result->fetch_assoc();

// Verificar autorização: paciente só pode editar suas próprias consultas
if ($tipo_usuario === 'paciente' && $consulta['id_paciente'] != $user_id) {
    die("Você não tem permissão para editar esta consulta.");
}
// (Opcional) Se for clínica, poderia editar qualquer… ou verificar se a clínica corresponde à consulta

// Apenas permitimos editar se status for, por exemplo, ‘agendada’
if ($consulta['status'] !== 'agendada') {
    die("Essa consulta não pode ser editada.");
}

// Formulário de edição
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Consulta - Clinic Help</title>
<link rel="stylesheet" href="../css/agendar.css">
<link rel="icon" href="../image/clinichelpicon.png">
</head>
<body>

<main class="agendamento">
  <h2>Editar Consulta</h2>
  <form action="salvar_edicao_consulta.php" method="POST" class="form-agendar">
    <input type="hidden" name="id_consulta" value="<?php echo htmlspecialchars($consulta_id); ?>">
    <label for="data">Nova data:</label>
    <input type="date" id="data" name="data" value="<?php echo htmlspecialchars($consulta['dia']); ?>" required>

    <label for="hora">Novo horário:</label>
    <input type="time" id="hora" name="hora" value="<?php echo htmlspecialchars(substr($consulta['hora'],0,5)); ?>" required>

    <button type="submit">Salvar Alterações</button>
  </form>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputData = document.getElementById('data');
    const inputHora = document.getElementById('hora');
    const hoje = new Date();
    const ano = hoje.getFullYear();
    const mes = String(hoje.getMonth() + 1).padStart(2, '0');
    const dia = String(hoje.getDate()).padStart(2, '0');
    inputData.min = `${ano}-${mes}-${dia}`;
});
</script>

</body>
</html>
