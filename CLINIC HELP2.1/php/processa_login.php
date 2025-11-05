<?php
// processa_login.php (arquivo de debug) - coloque no lugar do atual, faça testes e depois restaure
session_start();
include('conexao.php');

// DEBUG: habilitar/desabilitar depuração
$DEBUG = true;

// Sanitização mínima
$login = isset($_POST['login']) ? trim($_POST['login']) : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';

if ($DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

if ($login === '' || $senha === '') {
    if ($DEBUG) echo "DEBUG: login ou senha vazios. login=[$login]\n";
    echo "<script>alert('Preencha login e senha'); window.location='login.php';</script>";
    exit;
}

// --- PREPARE principal ---
$stmt = $conn->prepare("SELECT id, senha, tipo FROM usuario WHERE login = ?");

if (!$stmt) {
    $err = $conn->error;
    if ($DEBUG) {
        echo "DEBUG: Falha no prepare(): " . htmlspecialchars($err) . "<br>";
        // Tentar uma query direta para ver se a tabela/coluna existem:
        $q = "SHOW COLUMNS FROM usuario";
        $res = $conn->query($q);
        echo "<pre>DEBUG: Colunas da tabela usuario:\n";
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                echo htmlspecialchars($row['Field']) . " (" . htmlspecialchars($row['Type']) . ")\n";
            }
        } else {
            echo "Erro ao listar colunas: " . htmlspecialchars($conn->error) . "\n";
        }
        echo "</pre>";
    }
    die("Erro ao preparar SELECT do usuário. Ver DEBUG.");
}

if (! $stmt->bind_param("s", $login)) {
    if ($DEBUG) echo "DEBUG: bind_param retornou false: " . htmlspecialchars($stmt->error) . "<br>";
    die("Erro no bind_param. Ver DEBUG.");
}

if (! $stmt->execute()) {
    if ($DEBUG) echo "DEBUG: execute retornou false: " . htmlspecialchars($stmt->error) . "<br>";
    die("Erro ao executar query. Ver DEBUG.");
}

// try to use store_result to get num_rows
if (! $stmt->store_result()) {
    if ($DEBUG) echo "DEBUG: store_result falhou: " . htmlspecialchars($stmt->error) . "<br>";
    // continue mesmo assim
}

$num = $stmt->num_rows;
if ($DEBUG) echo "DEBUG: SELECT usuario -> linhas encontradas: $num<br>";

// Se não encontrou, vamos fazer uma checagem extra: buscar por LIKE e mostrar o conteúdo
if ($num === 0) {
    if ($DEBUG) {
        echo "<strong>DEBUG:</strong> Nenhum usuário encontrado com login exato: [{$login}]<br>";
        echo "Tentando buscas semelhantes:<br><pre>";
        $safe = $conn->real_escape_string($login);
        $q = "SELECT id, login, senha, tipo FROM usuario WHERE login LIKE '%{$safe}%' LIMIT 10";
        $r = $conn->query($q);
        if ($r) {
            while ($row = $r->fetch_assoc()) {
                echo "id={$row['id']} | login=[" . htmlspecialchars($row['login']) . "] | tipo=[" . htmlspecialchars($row['tipo']) . "] | senha_db=[" . htmlspecialchars(substr($row['senha'],0,30)) . "...] \n";
            }
        } else {
            echo "Erro ao executar LIKE: " . htmlspecialchars($conn->error) . "\n";
        }
        echo "</pre>";
    }
    echo "<script>alert('Login não encontrado ou senha incorreta'); window.location='login.php';</script>";
    exit;
}

// Se encontrou, bind dos resultados
if (! $stmt->bind_result($usuario_id, $hash, $tipo_usuario)) {
    if ($DEBUG) echo "DEBUG: bind_result falhou: " . htmlspecialchars($stmt->error) . "<br>";
    die("Erro no bind_result. Ver DEBUG.");
}

if (! $stmt->fetch()) {
    if ($DEBUG) echo "DEBUG: fetch retornou false (ou não conseguiu buscar). Erro: " . htmlspecialchars($stmt->error) . "<br>";
    // Ainda assim continuar para tentar analisar $hash se preenchido
}

// Mostrar valores obtidos (para debug)
if ($DEBUG) {
    echo "<pre>DEBUG: valores do usuario:\n";
    echo "usuario_id = " . htmlspecialchars($usuario_id) . "\n";
    echo "hash       = " . htmlspecialchars($hash) . "\n";
    echo "tipo       = " . htmlspecialchars($tipo_usuario) . "\n";
    echo "login usado= " . htmlspecialchars($login) . "\n";
    echo "</pre>";
}

// Verificar o hash
$senha_ok = false;
if ($hash === null || $hash === '') {
    if ($DEBUG) echo "DEBUG: hash vazio no banco (senha possivelmente salva sem hash).<br>";
    // testar igualdade direta (apenas para depuração)
    if ($senha === $hash) {
        $senha_ok = true;
    }
} else {
    // senha verificação normal
    if (password_verify($senha, $hash)) {
        $senha_ok = true;
    } else {
        // Em caso de falha, mostrar info adicional em debug
        if ($DEBUG) {
            echo "DEBUG: password_verify retornou false.<br>";
            // mostrar primeiros bytes do hash para inspecionar formato
            echo "DEBUG: prefixo do hash: " . htmlspecialchars(substr($hash,0,10)) . "<br>";
        }
    }
}

if (! $senha_ok) {
    echo "<script>alert('Senha incorreta.'); window.location='login.php';</script>";
    exit;
}

// Se chegou aqui, senha ok -> montar sessão
$_SESSION['usuario_id'] = $usuario_id;
$_SESSION['login'] = $login;
$_SESSION['tipo']  = $tipo_usuario;
$_SESSION['logado'] = true;

if ($DEBUG) echo "DEBUG: autenticação OK. tipo = " . htmlspecialchars($tipo_usuario) . "<br>";

// Agora tratar redirecionamento por tipo
$tipo_normalizado = trim(strtolower($tipo_usuario));

if ($tipo_normalizado === 'paciente') {
    $stmt2 = $conn->prepare("SELECT id FROM pacientes WHERE email = ?");
    if (!$stmt2) { if ($DEBUG) echo "DEBUG: prepare pacientes erro: ".$conn->error; die("Erro"); }
    $stmt2->bind_param("s", $login);
    $stmt2->execute();
    $stmt2->store_result();
    if ($DEBUG) echo "DEBUG: pacientes rows = " . $stmt2->num_rows . "<br>";
    if ($stmt2->num_rows === 1) {
        $stmt2->bind_result($paciente_id);
        $stmt2->fetch();
        $_SESSION['paciente_id'] = $paciente_id;
        header("Location: Tclinicas.php");
        exit;
    } else {
        echo "<script>alert('Paciente não encontrado no banco de dados.'); window.location='login.php';</script>";
        exit;
    }
}

if ($tipo_normalizado === 'clinica') {
    $stmt3 = $conn->prepare("SELECT id FROM clinicas_ WHERE email = ?");
    if (!$stmt3) { if ($DEBUG) echo "DEBUG: prepare clinicas erro: ".$conn->error; die("Erro"); }
    $stmt3->bind_param("s", $login);
    $stmt3->execute();
    $stmt3->store_result();
    if ($DEBUG) echo "DEBUG: clinicas rows = " . $stmt3->num_rows . "<br>";
    if ($stmt3->num_rows === 1) {
        $stmt3->bind_result($clinica_id);
        $stmt3->fetch();
        $_SESSION['clinica_id'] = $clinica_id;
        header("Location: Tclinicas.php");
        exit;
    } else {
        echo "<script>alert('Clínica não encontrada no banco de dados.'); window.location='login.php';</script>";
        exit;
    }
}

if ($tipo_normalizado === 'medico') {
    $stmt4 = $conn->prepare("SELECT id FROM medico WHERE email = ?");
    if (!$stmt4) { if ($DEBUG) echo "DEBUG: prepare medico erro: ".$conn->error; die("Erro"); }
    $stmt4->bind_param("s", $login);
    $stmt4->execute();
    $stmt4->store_result();
    if ($DEBUG) echo "DEBUG: medico rows = " . $stmt4->num_rows . "<br>";
    if ($stmt4->num_rows === 1) {
        $stmt4->bind_result($medico_id);
        $stmt4->fetch();
        $_SESSION['medico_id'] = $medico_id;
        header("Location: Tclinicas.php");
        exit;
    } else {
        echo "<script>alert('Médico não encontrado no banco de dados.'); window.location='login.php';</script>";
        exit;
    }
}

// Caso não seja nenhum tipo
echo "<script>alert('Tipo de conta inválido ou não associado.'); window.location='login.php';</script>";
exit;
?>
