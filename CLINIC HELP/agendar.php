<!DOCTYPE html>
<html lang="pt-br">
<head>

<?php
include('verificacao.php');
$id_clin_med = $_POST["id_clin_med"];
$id_paciente = $_SESSION["usuario_id"];
?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Agendar Consulta - Clinic Help</title>
  <link rel="stylesheet" href="agendar.css">
  <link rel="icon" href="clinichelpicon.png">
</head>
<body>

  <header>
    <nav>
      <ul class="tabs">
        <li><a href="site.html"><img src="school-house-icon32x32.png"></a></li>
      </ul>
    </nav>
  </header>

  <main class="agendamento">
    <h2>Agendar Consulta</h2>
    <form action="agenda.php" method="POST" class="form-agendar">
    <input type="hidden" name="id_pacientes" value="<?php echo $id_paciente; ?>">
    <input type="hidden" name="id_clin_med" value="<?php echo $id_clin_med; ?>">
      <label for="clinica">Médico:</label>
      <select id="clinica" name="clinica" required>
        <option value="">Selecione</option>
       <option value="Dr.Celso Zuther">Dr. Celso Zuther</option>
       <option value="Dr. Henrique Gazola">Dr. Henrique Gazola</option>
        <option value="Dr. Odilor Gonçalves">Dr. Odilor Gonçalves</option>
        <option value="Dr. Márcio Pires">Dr. Márcio Pires</option>
        <option value="Dra. Patrícia Zanette">Dra. Patrícia Zanette</option>
        <option value="Dra. Tatiane Oliveira">Dra. Tatiane Oliveira</option>
        <option value="Dra. Ana Claudia Zimmermann">Dra. Ana Claudia Zimmermann</option>
      </select>

      <label for="data">Data da consulta:</label>
      <input type="date" id="data" name="data" required>
<script>
    document.addEventListener('DOMContentLoaded', () => {
      const inputData = document.getElementById('data');
      const hoje = new Date();
      const ano = hoje.getFullYear();
      const mes = String(hoje.getMonth() + 1).padStart(2, '0');
      const dia = String(hoje.getDate()).padStart(2, '0');
      inputData.min = `${ano}-${mes}-${dia}`;
    });
  </script>
      <label for="hora">Horário:</label>
      <input type="time" id="hora" name="hora" required>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const inputData = document.getElementById('data');
  const inputHora = document.getElementById('hora');
  function atualizarHorarioMinimo() {
    const hoje = new Date();
    const dataSelecionada = new Date(inputData.value);
    if (!inputData.value) {
      inputHora.removeAttribute('min');
      return;
    }
    const hojeStr = hoje.toISOString().split('T')[0];
    if (inputData.value === hojeStr) {
      const horas = String(hoje.getHours()).padStart(2, '0');
      const minutos = String(hoje.getMinutes()).padStart(2, '0');
      inputHora.min = `${horas}:${minutos}`;
    } else {
      inputHora.removeAttribute('min');
    }
  }
  inputData.addEventListener('change', atualizarHorarioMinimo);
  atualizarHorarioMinimo();
});
</script>
      <button type="submit">Confirmar Agendamento</button>
    </form>
  </main>

</body>
</html>
