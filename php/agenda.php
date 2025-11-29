<?php
session_start();
include("conexao.php");

echo "<pre>";
var_dump($_SESSION);
var_dump($_POST);
echo "</pre>";

if (!isset($_SESSION['paciente_id'])) {
    echo "<script>alert('Você precisa estar logado para agendar uma consulta.'); window.location='login.php';</script>";
    exit;
}

$id_paciente = $_SESSION['paciente_id'];
$id_clin_med = $_POST['id_clin_med'] ?? null;
$data        = $_POST['data']        ?? null;
$hora        = $_POST['hora']        ?? null;

if (!$id_paciente || !$id_clin_med || !$data || !$hora) {
    die("Erro: todos os campos são obrigatórios.");
}

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
