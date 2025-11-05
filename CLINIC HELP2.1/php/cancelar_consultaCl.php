<?php
session_start();
include("conexao.php");

// Verifica se usuário está logado
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
    // talvez permitir médico ou não
    die("Acesso inválido.");
}

if (!$user_id) {
    die("Usuário não identificado.");
}

// Recebe o ID da consulta via GET
$id_consulta = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_consulta <= 0) {
    die("Consulta inválida.");
}

// Verificar se a consulta existe e pertence ao usuário (ou à clínica)
$sql = "SELECT id_paciente, id_clin_med, status FROM agendados WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_consulta);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
    die("Consulta não encontrada.");
}
$consulta = $res->fetch_assoc();

// Verificar permissão
if ($tipo_usuario === 'paciente' && $consulta['id_paciente'] != $user_id) {
    die("Você não tem permissão para cancelar esta consulta.");
}
if ($tipo_usuario === 'clinica') {
    // opcional: verificar se a clínica corresponde ao id_clin_med
    // você pode fazer join para confirmar
}

// Ação: deletar ou apenas marcar como cancelada
// Exemplo: deletar
$sql_delete = "DELETE FROM agendados WHERE id = ?";
$stmt2 = $conn->prepare($sql_delete);
$stmt2->bind_param("i", $id_consulta);

if ($stmt2->execute()) {
    echo "<script>alert('Consulta cancelada com sucesso!'); window.location='TagendadosClin.php';</script>";
} else {
    echo "Erro ao cancelar consulta: " . htmlspecialchars($stmt2->error);
}

$stmt2->close();
$stmt->close();
$conn->close();
?>
