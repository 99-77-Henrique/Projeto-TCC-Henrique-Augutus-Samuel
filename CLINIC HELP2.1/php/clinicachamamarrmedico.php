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
?>

<main class="medicos">
  <h2>Pesquisar Médicos</h2>
  <input type="text" id="search" class="search" placeholder="Digite o nome do médico...">

  <section id="lista-medicos" class="lista-medicos">
  <?php
    // Lista todos os médicos cadastrados
    $sql = "SELECT id, nome, telefone, especializacao FROM medico ORDER BY nome ASC";
    $resultado = $conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
      while ($row = $resultado->fetch_assoc()) {
        echo '
          <div class="medico-card"
               data-nome="' . htmlspecialchars(strtolower($row["nome"])) . '"
               style="display:none;">
            <h3>' . htmlspecialchars($row["nome"]) . '</h3>
            <p><strong>Especialização:</strong> ' . htmlspecialchars($row["especializacao"]) . '</p>
            <p><strong>Telefone:</strong> ' . htmlspecialchars($row["telefone"]) . '</p>
            <form method="POST" action="adicionar_medico.php" style="margin-top:10px;">
              <input type="hidden" name="id_medico" value="' . $row["id"] . '">
              <button type="submit" class="btn-add">Adicionar à Clínica</button>
            </form>
          </div>';
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

<!-- Acessibilidade VLibras -->
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

  // Pesquisa dinâmica
  searchInput.addEventListener('input', function() {
    const filtro = this.value.toLowerCase();

    medicoCards.forEach(card => {
      const nome = card.getAttribute('data-nome');
      card.style.display = (filtro && nome.startsWith(filtro)) ? 'block' : 'none';
    });
  });
</script>

</body>
</html>
