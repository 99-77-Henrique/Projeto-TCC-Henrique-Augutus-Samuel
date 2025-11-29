<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/agendados.css">
  <link rel="icon" href="../image/clinichelpicon.png">
  <title>Agendados - Clinic Help</title>
</head>
<body>
 <?php
include("menu.php");
?>

  <div class="container">
    <?php
    include("conexao.php");
    $sql = "
        SELECT a.id,
               m.nome AS medico,
               m.especializacao,
               a.dia,
               a.hora
        FROM agendados a
        JOIN medico m ON a.id_clin_med = m.id
        ORDER BY a.dia, a.hora
    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){
            $data_formatada = date("d \d\e F \d\e Y", strtotime($row['dia']));
            $hora_formatada = date("H:i", strtotime($row['hora']));
    ?>
        <div class="card">
          <div>
            <h3><?php echo htmlspecialchars($row['medico']); ?></h3>
            <p><?php echo htmlspecialchars($row['especializacao']); ?></p>
            <p><?php echo $data_formatada . " às " . $hora_formatada; ?></p>
          </div>
          <div class="actions">
            <button class="btn edit"></button>
            <button class="btn delete"></button>
          </div>
        </div>
    <?php
        }
    } else {
    ?>
        <p>Nenhuma consulta encontrada.</p>
    <?php
    }
    ?>
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
  </div>
</body>
</html>
