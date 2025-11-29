<?php
include("conexao.php");
session_start();


if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'paciente') {
    header("Location: login.php");
    exit;
}


if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID da consulta inválido.");
}

$id_consulta = intval($_GET['id']);
$id_paciente = $_SESSION['paciente_id'] ?? null;


$sql = "UPDATE agendados SET status = 'confirmada' WHERE id = ? AND id_paciente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_consulta, $id_paciente);

if ($stmt->execute()) {
    
    header("Location: Tagendados.php");
    exit;
} else {
    echo "Erro ao confirmar consulta.";
}
?>
