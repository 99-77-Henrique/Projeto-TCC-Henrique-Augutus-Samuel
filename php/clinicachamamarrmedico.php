<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Relacionar Médicos - Clinic Help</title>
  <link rel="stylesheet" href="../css/clinicachamamarmedico.css">
  <link rel="icon" href="../image/clinichelpicon.png">
</head>
<body>

<?php
include("menu.php");
include("conexao.php");

if (!isset($_SESSION['clinica_id'])) {
  echo "<script>alert('Apenas clínicas podem adicionar médicos.'); window.location='login.php';</script>";
  exit;
}

$id_clinica = $_SESSION['clinica_id'];

if (isset($_POST['recontratar_id'])) {
    $id_medico = $_POST['recontratar_id'];

    $sql_update = "UPDATE clin_med SET situacao = 1 WHERE id_clinica = ? AND id_medicos = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ii", $id_clinica, $id_medico);

    if ($stmt_update->execute()) {
        echo "<script>alert('Médico recontratado com sucesso!'); window.location='clinicachamamarrmedico.php';</script>";
        exit;
    } else {
        echo "<script>alert('Erro ao recontratar médico.');</script>";
    }
}
?>

<main class="medicos">
  <h2 class="pesquisar">Pesquisar Médicos</h2>
  <input type="text" id="search" class="search" placeholder="Digite o nome do médico...">

  <section id="lista-medicos" class="lista-medicos">
  <?php
    $sql = "SELECT m.id, m.nome, m.telefone, m.especializacao, cm.situacao
            FROM medico m
            LEFT JOIN clin_med cm 
              ON m.id = cm.id_medicos AND cm.id_clinica = ?
            ORDER BY m.nome ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_clinica);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
      while ($row = $resultado->fetch_assoc()) {
        $nome = htmlspecialchars($row["nome"]);
        $esp = htmlspecialchars($row["especializacao"]);
        $tel = htmlspecialchars($row["telefone"]);
        $id_med = $row["id"];
        $situacao = $row["situacao"];

        echo '<div class="medico-card" data-nome="' . strtolower($nome) . '" style="display:none;">
                <h3>' . $nome . '</h3>
                <p><strong>Especialização:</strong> ' . $esp . '</p>
                <p><strong>Telefone:</strong> ' . $tel . '</p>';

        if (is_null($situacao)) {
          echo '<form method="POST" action="adicionar_medico.php" style="margin-top:10px;">
                  <input type="hidden" name="id_medico" value="' . $id_med . '">
                  <input type="hidden" name="situacao" value="1">
                  <button type="submit" class="btn-add">Adicionar à Clínica</button>
                </form>';
        }
        elseif ($situacao == 0) {
          echo '<form method="POST" onsubmit="return confirmarRecontratar();">
                  <input type="hidden" name="recontratar_id" value="' . $id_med . '">
                  <button type="submit" class="btn-recontratar">Recontratar Médico</button>
                </form>';
        }
        elseif ($situacao == 1) {
          echo '<p style="color:green; font-weight:bold;">✅ Já vinculado à clínica</p>';
        }

        echo '</div>';
      }
    } else {
      echo "<p>Nenhum médico encontrado.</p>";
    }
  ?>
  </section>
</main>

<footer>
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

  const searchInput = document.getElementById('search');
  const medicoCards = document.querySelectorAll('.medico-card');

  searchInput.addEventListener('input', function() {
    const filtro = this.value.toLowerCase();
    medicoCards.forEach(card => {
      const nome = card.getAttribute('data-nome');
      card.style.display = (filtro && nome.startsWith(filtro)) ? 'block' : 'none';
    });
  });

  function confirmarRecontratar() {
    return confirm("Tem certeza que deseja recontratar este médico?");
  }
</script>

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
