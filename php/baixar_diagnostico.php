<?php
include("conexao.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "SELECT documento FROM diagnostico WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($pdf);
        $stmt->fetch();

        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=diagnostico_$id.pdf");
        echo $pdf;
    } else {
        echo "<script>alert('Diagnóstico não encontrado.'); history.back();</script>";
    }

    $stmt->close();
}
?>
