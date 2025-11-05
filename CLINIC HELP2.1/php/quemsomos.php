<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quem Somos - Clinic Help</title>
  <link rel="stylesheet" href="../css/quemsomos.css">
  <link rel="icon" href="../image/clinichelpicon.png">
</head>
<body>

<?php
include("menu.php");
?>

  <main class="quem-somos">
    <section class="conteudo">
      <h1>Quem Somos</h1>
      <p>A <strong>Clinic Help</strong> nasceu com o propósito de conectar pacientes e clínicas de forma simples, rápida e eficiente. Nosso objetivo é facilitar o agendamento de consultas e tornar o acesso à saúde mais acessível para todos.</p>
      
      <p>Com uma equipe comprometida, oferecemos uma plataforma intuitiva e segura para você encontrar a especialidade que precisa, no local e horário que melhor se encaixam na sua rotina.</p>
      
      <p>Estamos sempre em busca de inovação, pensando no seu bem-estar e no futuro da saúde digital. Junte-se a nós nessa jornada por uma saúde mais humana, acessível e inteligente.</p>
    </section>

    <section class="imagem">
      <img src="../image/medico.avif" alt="Equipe médica" class="img-quemsomos">
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
  </script>
  
</body>
</html>
