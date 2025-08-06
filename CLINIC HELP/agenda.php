<?php
date_default_timezone_set('America/Sao_Paulo');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $id_clin_med = $_POST['id_clin_med']; 
    $id_paciente = $_POST['id_pacientes']; 
echo "$id_clin_med";
    $agendada = new DateTime("$data $hora");
    if ($agendada < new DateTime('now')) {
        die('Não é possível agendar para data/hora passadas.');
    }

    include('conexao.php');

    $stmt = $conn->prepare("SELECT COUNT(*) FROM agendados WHERE dia = ? AND hora = ? AND id_clin_med = ?");
    $stmt->bind_param('ssi', $data, $hora, $id_clin_med);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        die('Este horário já está agendado para esta clínica/médico.');
    }

    $stmt = $conn->prepare("INSERT INTO agendados (dia, hora, id_clin_med, id_usuarios) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssii', $data, $hora, $id_clin_med, $id_paciente);
    if ($stmt->execute()) {
        echo "<script>alert('Agendado com sucesso'); window.location='clinicas.php';</script>";
    } else {
        echo 'Erro ao inserir agendamento: ' . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>
