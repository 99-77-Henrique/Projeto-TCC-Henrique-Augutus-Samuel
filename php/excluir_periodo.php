<?php
include("conexao.php");

$clinmed = $_GET['clinmed'];
$inicio = $_GET['inicio'];
$fim = $_GET['fim'];

$sql = "DELETE FROM agendados 
        WHERE id_clin_med = '$clinmed'
        AND hora IN ('$inicio', '$fim')";

if ($conn->query($sql)) {
    header("Location: tempomedico.php?msg=excluido");
    exit;
} else {
    echo "Erro ao excluir período.";
}
?>
