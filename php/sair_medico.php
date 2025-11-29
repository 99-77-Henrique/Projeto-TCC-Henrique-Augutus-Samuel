<?php
include("menu.php");
include("conexao.php");


if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'clinica') {
    header("Location: login.php");
    exit;
}

$id_clinica = $_SESSION['clinica_id'] ?? null;
if (!$id_clinica) {
    die("Erro: ID da clínica não encontrado na sessão.");
}

if (isset($_POST['id_medico'])) {
    $id_medico = $_POST['id_medico'];

    $sql_update = "UPDATE clin_med SET situacao = 0 WHERE id_clinica = ? AND id_medicos = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ii", $id_clinica, $id_medico);

    if ($stmt_update->execute()) {
        echo "<script>alert('Médico retirado com sucesso!'); window.location='sair_medico.php';</script>";
        exit;
    } else {
        echo "<script>alert('Erro ao retirar médico.');</script>";
    }
    $stmt_update->close();
}

$sql = "SELECT m.id, m.nome, m.especializacao, m.telefone 
        FROM clin_med cm
        JOIN medico m ON cm.id_medicos = m.id
        WHERE cm.id_clinica = ? AND cm.situacao = 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_clinica);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Retirar Médico - Clinic Help</title>
  <link rel="icon" href="../image/clinichelpicon.png">
  <link rel="stylesheet" href="../css/agendados.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    .container {
      max-width: 800px;
      margin: 60px auto;
      padding: 30px;
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .card {
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .btn-retirar {
      background-color: #dc3545;
      color: #fff;
      padding: 10px 18px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s;
    }
    .btn-retirar:hover {
      opacity: 0.8;
    }
  </style>
</head>
<body>

<div class="container">
  <h2 style="text-align:center; margin-bottom:20px;">Médicos Vinculados à Clínica</h2>

  <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
          <div class="card">
              <div>
                  <p><strong>Nome:</strong> <?= htmlspecialchars($row['nome']); ?></p>
                  <p><strong>Especialização:</strong> <?= htmlspecialchars($row['especializacao']); ?></p>
                  <p><strong>Telefone:</strong> <?= htmlspecialchars($row['telefone']); ?></p>
              </div>
              <form method="POST" onsubmit="return confirm('Deseja realmente retirar este médico da clínica?');">
                  <input type="hidden" name="id_medico" value="<?= $row['id']; ?>">
                  <button type="submit" class="btn-retirar">Retirar</button>
              </form>
          </div>
      <?php endwhile; ?>
  <?php else: ?>
      <p style="text-align:center;">Nenhum médico vinculado à clínica no momento.</p>
  <?php endif; ?>
</div>

<footer style="text-align:center; margin-top:50px;">
  <p>&#169; 2025 CLINIC HELP. Todos os direitos reservados.</p>
</footer>

<div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
  </div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
  </script>

</body>
</html>
