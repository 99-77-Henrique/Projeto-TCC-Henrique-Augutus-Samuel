<?php
session_start();
include("conexao.php");

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'medico') {
    header("Location: login.php");
    exit;
}

$id_medico = $_SESSION['medico_id'] ?? null;
$id_relacao = $_POST['id_relacao'] ?? null;

if (!$id_medico || !$id_relacao) {
    echo "<script>alert('Erro ao processar requisição.'); window.location='TagendadosMed.php';</script>";
    exit;
}

$sql = "UPDATE clin_med SET situacao = 0 WHERE id = ? AND id_medicos = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_relacao, $id_medico);

if ($stmt->execute()) {
    echo "<script>alert('Você saiu da clínica com sucesso.'); window.location='TagendadosMed.php';</script>";
} else {
    echo "<script>alert('Erro ao sair da clínica.'); window.location='TagendadosMed.php';</script>";
}
?>
