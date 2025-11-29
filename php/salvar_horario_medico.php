<?php
session_start();
include("conexao.php");

$id_medico = $_SESSION['medico_id'];
$id_clin_med = $_POST['id_clin_med'];
$inicio = $_POST['inicio'];
$fim = $_POST['fim'];

if (!$id_medico || !$id_clin_med) {
    die("Erro: médico ou clínica não encontrados.");
}

$sql_check = "
SELECT a.hora, a.status
FROM agendados a
JOIN clin_med cm ON cm.id = a.id_clin_med
WHERE cm.id_medicos = '$id_medico'
AND a.id_clin_med != '$id_clin_med'
AND a.id_paciente = 0
ORDER BY a.hora
";

$res = $conn->query($sql_check);

$horarios = [];
while ($row = $res->fetch_assoc()) {
    $horarios[] = $row;
}

$intervalos = [];
for ($i=0; $i < count($horarios); $i+=2) {
    if (isset($horarios[$i], $horarios[$i+1])) {
        $h_inicio = $horarios[$i]['hora'];
        $h_fim    = $horarios[$i+1]['hora'];
        $intervalos[] = [$h_inicio, $h_fim];
    }
}

foreach ($intervalos as [$ini_exist, $fim_exist]) {
    if (
        ($inicio < $fim_exist) &&
        ($fim > $ini_exist)
    ) {
        die("⚠ ERRO: Esse horário entra em conflito com outra clínica ($ini_exist - $fim_exist).");
    }
}

$sql_i = "INSERT INTO agendados 
(id_paciente, id_clin_med, dia, hora, status, email_enviado)
VALUES
(0, '$id_clin_med', '*', '$inicio', 'inicio', 0)";
$conn->query($sql_i);

$sql_f = "INSERT INTO agendados 
(id_paciente, id_clin_med, dia, hora, status, email_enviado)
VALUES
(0, '$id_clin_med', '*', '$fim', 'fim', 0)";
$conn->query($sql_f);

header("location: tempomedico.php");
?>
