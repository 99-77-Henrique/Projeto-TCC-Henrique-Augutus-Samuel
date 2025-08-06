<?php
$dbname = "clinica_db";

$conn = mysqli_connect("localhost", "root", "");

if (!$conn) {
    echo "Erro na conexão com o MySQL.";
    exit;
}
if (!mysqli_select_db($conn, $dbname)) {
    echo "Erro ao selecionar banco de dados.";
    exit;
}
?>
