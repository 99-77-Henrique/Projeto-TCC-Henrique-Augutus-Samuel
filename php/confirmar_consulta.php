<?php
include("conexao.php");
session_start();


if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'clinica') {
    header("Location: login.php");
    exit;
}


if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Erro: ID da consulta não informado.");
}

$id_consulta = intval($_GET['id']);


$sql = "UPDATE agendados SET status = 'confirmar' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_consulta);

if ($stmt->execute()) {

    header("Location: TagendadosClin.php?msg=confirmar");
    exit;
} else {
    echo "Erro ao confirmar a consulta: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
