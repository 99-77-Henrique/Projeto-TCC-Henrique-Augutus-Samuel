<?php
session_start();
include("conexao.php");

if (!isset($_SESSION['clinica_id'])) {
  echo "<script>alert('Apenas clínicas podem adicionar médicos.'); window.location='login.php';</script>";
  exit;
}

$id_clinica = $_SESSION['clinica_id'];
$id_medico = $_POST['id_medico'] ?? null;

if (!$id_medico) {
  echo "<script>alert('ID do médico inválido.'); window.location='clinicachamamarrmedico.php';</script>";
  exit;
}

$sql_check = "SELECT * FROM clin_med WHERE id_clinica = ? AND id_medicos = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $id_clinica, $id_medico);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
  echo "<script>alert('Este médico já está vinculado à sua clínica.'); window.location='clinicachamamarrmedico.php';</script>";
  exit;
}

$sql = "INSERT INTO clin_med (id_clinica, id_medicos, situacao) VALUES (?, ?, 1)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_clinica, $id_medico);

if ($stmt->execute()) {
  echo "<script>alert('Médico adicionado com sucesso!'); window.location='clinicachamamarrmedico.php';</script>";
} else {
  echo "<script>alert('Erro ao adicionar médico.'); window.location='clinicachamamarrmedico.php';</script>";
}
?>
