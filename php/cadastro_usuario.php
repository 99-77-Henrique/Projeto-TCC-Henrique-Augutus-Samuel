<?php
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cadastrar_clinica'])) {
    echo "Entrou no IF da clínica<br>";
    flush();

    $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $sqlClinica = "INSERT INTO clinicas_ (nome, CNPJ, telefone, Cidade, estado, email)
                   VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlClinica);
    if (!$stmt) {
        die("Erro ao preparar SQL da clínica: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssss",
        $_POST['nome'],
        $_POST['cnpj'],
        $_POST['telefone'],
        $_POST['cidade'],
        $_POST['estado'],
        $_POST['email']
    );
    $stmt->execute();
    $stmt->close();

    $sqlUsuario = "INSERT INTO usuario (login, senha, tipo) VALUES (?, ?, ?)";
    $stmtUser = $conn->prepare($sqlUsuario);
    if (!$stmtUser) {
        die("Erro ao preparar SQL do usuário: " . $conn->error);
    }

    $tipo = "clinica";
    $stmtUser->bind_param("sss", $_POST['email'], $senhaHash, $tipo);
    $stmtUser->execute();
    $stmtUser->close();


    echo "
    <form id='redirectForm' method='POST' action='processa_login.php'>
        <input type='hidden' name='login' value='{$_POST['email']}'>
        <input type='hidden' name='senha' value='{$_POST['senha']}'>
        <input type='hidden' name='tipo' value='clinica'>
    </form>
    <script>document.getElementById('redirectForm').submit();</script>
    ";
    exit;
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cadastrar_paciente'])) {
    $sqlPaciente = "INSERT INTO pacientes (Nome_paciente, telefone, CPF, CEP, email)
                    VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sqlPaciente);
    if (!$stmt) {
        die("Erro ao preparar SQL do paciente: " . $conn->error);
    }

    $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt->bind_param(
        "sssss",
        $_POST['nome'],
        $_POST['telefone'],
        $_POST['cpf'],
        $_POST['cep'],
        $_POST['email']
    );
    $stmt->execute();
    $stmt->close();

    $sqlUsuario = "INSERT INTO usuario (login, senha, tipo) VALUES (?, ?, ?)";
    $stmtUser = $conn->prepare($sqlUsuario);
    if (!$stmtUser) {
        die("Erro ao preparar SQL do usuário (paciente): " . $conn->error);
    }

    $tipo = "paciente";
    $stmtUser->bind_param("sss", $_POST['email'], $senhaHash, $tipo);
    $stmtUser->execute();
    $stmtUser->close();


    echo "
    <form id='redirectForm' method='POST' action='processa_login.php'>
        <input type='hidden' name='login' value='{$_POST['email']}'>
        <input type='hidden' name='senha' value='{$_POST['senha']}'>
        <input type='hidden' name='tipo' value='paciente'>
    </form>
    <script>document.getElementById('redirectForm').submit();</script>
    ";
    exit;
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cadastrar_medico'])) {
    $sqlMedico = "INSERT INTO medico (nome, email, telefone, crm, cpf, especializacao)
                    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlMedico);
    if (!$stmt) {
        die("Erro ao preparar SQL do médico: " . $conn->error);
    }

    $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt->bind_param(
        "ssssss",
        $_POST['nome'],
        $_POST['email'],
        $_POST['telefone'],
        $_POST['crm'],
        $_POST['cpf'],
        $_POST['especializacao']
    );
    $stmt->execute();
    $stmt->close();

    $sqlUsuario = "INSERT INTO usuario (login, senha, tipo) VALUES (?, ?, ?)";
    $stmtUser = $conn->prepare($sqlUsuario);
    if (!$stmtUser) {
        die("Erro ao preparar SQL do usuário (médico): " . $conn->error);
    }

    $tipo = "medico";
    $stmtUser->bind_param("sss", $_POST['email'], $senhaHash, $tipo);
    $stmtUser->execute();
    $stmtUser->close();


    echo "
    <form id='redirectForm' method='POST' action='processa_login.php'>
        <input type='hidden' name='login' value='{$_POST['email']}'>
        <input type='hidden' name='senha' value='{$_POST['senha']}'>
        <input type='hidden' name='tipo' value='medico'>
    </form>
    <script>document.getElementById('redirectForm').submit();</script>
    ";
    exit;
}
?>
