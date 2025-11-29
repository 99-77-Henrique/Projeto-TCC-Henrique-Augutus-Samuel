<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contato - Clinic Help</title>
  <link rel="stylesheet" href="../css/contato.css">
  <link rel="icon" href="clinichelpicon.png">
</head>
<body>
<?php
include("menu.php");
?>

  <main class="contato">
    <section class="formulario-contato">
      <h1>Fale Conosco</h1>
      <p>Preencha o formulário abaixo e entraremos em contato com você o mais rápido possível.</p>

      <form action="enviar_email.php" method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>

        <label for="mensagem">Mensagem:</label>
        <textarea id="mensagem" name="mensagem" rows="5" required></textarea>

        <button type="submit">Enviar</button>
      </form>
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
