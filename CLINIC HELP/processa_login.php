<?php
session_start();
include('conexao.php');

$login = trim($_POST['login'] ?? '');
$senha = $_POST['senha'] ?? '';

if (!$login || !$senha) {
    echo "<script>alert('Preencha login e senha'); window.location='login.php';</script>";
    exit;
}

$stmt = $conn->prepare("SELECT id, senha FROM usuario WHERE login = ?");
$stmt->bind_param("s", $login);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $hash);
    $stmt->fetch();

    if (password_verify($senha, $hash)) {
        $_SESSION['usuario_id'] = $id;
        header("Location: clinicas.php");
        exit;
    }
}

echo "<script>alert('Login ou senha incorretos'); window.location='login.php';</script>";
