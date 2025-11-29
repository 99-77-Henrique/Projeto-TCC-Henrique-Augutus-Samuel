<?php
include("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['documento']) && isset($_POST['id_agendados'])) {
    $id_agendados = intval($_POST['id_agendados']);
    $arquivoTmp = $_FILES['documento']['tmp_name'];

    if (file_exists($arquivoTmp)) {
        $pdf = file_get_contents($arquivoTmp);

     
        $sql = "INSERT INTO diagnostico (documento, id_agendados) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("bi", $null, $id_agendados);
        $stmt->send_long_data(0, $pdf);

        if ($stmt->execute()) {
        
            $update = $conn->prepare("UPDATE agendados SET status = 'concluido' WHERE id = ?");
            $update->bind_param("i", $id_agendados);
            $update->execute();
            $update->close();

            echo "<script>alert('Diagnóstico enviado e agendamento concluído com sucesso!'); window.location='TagendadosMed.php';</script>";
        } else {
            echo "<script>alert('Erro ao enviar diagnóstico.'); window.location='TagendadosMed.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Arquivo inválido.'); window.location='TagendadosMed.php';</script>";
    }
}
?>
