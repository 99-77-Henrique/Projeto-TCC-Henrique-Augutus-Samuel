<?php
include("conexao.php");

$id_clin_med = $_GET['id_clin_med'] ?? 0;

$resposta = [
    "inicio" => null,
    "fim" => null
];

if ($id_clin_med > 0) {


    $sql_inicio = "SELECT hora FROM agendados 
                   WHERE id_clin_med = ? AND status = 'inicio' LIMIT 1";
    $stmt = $conn->prepare($sql_inicio);
    $stmt->bind_param("i", $id_clin_med);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $resposta["inicio"] = $res->fetch_assoc()["hora"];
    }


    $sql_fim = "SELECT hora FROM agendados 
                WHERE id_clin_med = ? AND status = 'fim' LIMIT 1";
    $stmt = $conn->prepare($sql_fim);
    $stmt->bind_param("i", $id_clin_med);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $resposta["fim"] = $res->fetch_assoc()["hora"];
    }
}

header("Content-Type: application/json");
echo json_encode($resposta);
