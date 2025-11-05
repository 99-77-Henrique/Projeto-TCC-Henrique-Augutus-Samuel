<?php
session_start();
include("conexao.php");

// DEBUG: ver os dados que chegaram
echo "<pre>";
var_dump($_SESSION);
var_dump($_POST);
echo "</pre>";

// Verificar se o paciente está logado
if (!isset($_SESSION['paciente_id'])) {
    echo "<script>alert('Você precisa estar logado para agendar uma consulta.'); window.location='login.php';</script>";
    exit;
}

$id_paciente = $_SESSION['paciente_id'];
$id_clin_med = $_POST['id_clin_med'] ?? null;
$data        = $_POST['data']        ?? null;
$hora        = $_POST['hora']        ?? null;

// Verificar se todos os campos vieram corretos
if (!$id_paciente || !$id_clin_med || !$data || !$hora) {
    die("Erro: todos os campos são obrigatórios.");
}


// Preparar inserção no banco (tabela agendados)
$sql = "INSERT INTO agendados (id_paciente, id_clin_med, dia, hora, status)
        VALUES (?, ?, ?, ?, 'agendada')";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erro na preparação da consulta: " . $conn->error);
}

$stmt->bind_param("iiss", $id_paciente, $id_clin_med, $data, $hora);

if ($stmt->execute()) {
    echo "<script>alert('Consulta agendada com sucesso!'); window.location='Tclinicas.php';</script>";
} else {
    echo "Erro ao agendar consulta: " . htmlspecialchars($stmt->error);
}

$stmt->close();
$conn->close();
?>
