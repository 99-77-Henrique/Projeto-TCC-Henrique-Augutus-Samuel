<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Consulta - Clinic Help</title>
  <link rel="stylesheet" href="../css/editarconsulta.css">
</head>
<body>

 <?php
include("menu.php");
?>

  <!-- Formulário -->
  <div class="form-container">
    <h2>Editar consulta</h2>
    <form>
      <label for="medico">Médico:</label>
      <select id="medico" required>
        <option>Carlos fernando</option>
        <option>Maria Oliveira</option>
        <option>Henrique Souza</option>
      </select>

      <label for="data">Data consulta:</label>
      <input type="date" id="data" value="2025-12-16" required>

      <label for="hora">Horário:</label>
      <input type="time" id="hora" value="13:50" required>

      <button type="submit">Confirmar agendamento</button>
    </form>
  </div>

  <!-- Rodapé -->
  <footer>
    ©2025 CLINIC HELP. Todos os direitos reservados.
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
