<?php
session_start();
include("conexao.php");

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

$id_consulta = isset($_POST['id_consulta']) ? intval($_POST['id_consulta']) : 0;
$data       = $_POST['data'] ?? '';
$hora       = $_POST['hora'] ?? '';

if ($id_consulta <= 0 || !$data || !$hora) {
    die("Erro: todos os campos são obrigatórios.");
}

$sql = "SELECT id_paciente, id_clin_med, status FROM agendados WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_consulta);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows === 0) {
    die("Consulta não encontrada.");
}
$consulta = $res->fetch_assoc();

if ($tipo_usuario === 'paciente' && $consulta['id_paciente'] != $user_id) {
    die("Você não tem permissão para editar esta consulta.");
}

if ($consulta['status'] !== 'agendada') {
    die("Essa consulta não pode ser editada.");
}

$sql_up = "UPDATE agendados SET dia = ?, hora = ? WHERE id = ?";
$stmt_up = $conn->prepare($sql_up);
$stmt_up->bind_param("ssi", $data, $hora, $id_consulta);

if ($stmt_up->execute()) {
    echo "<script>alert('Consulta atualizada com sucesso!'); window.location='Tagendados.php';</script>";
} else {
    echo "Erro ao atualizar consulta: " . htmlspecialchars($stmt_up->error);
}
?>
