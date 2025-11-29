<?php
include("conexao.php");
include("menu.php");

$id_medico = $_SESSION['medico_id']; 
$sql_clin = "SELECT cm.id AS id_clin_med, c.nome
             FROM clin_med cm
             JOIN clinicas_ c ON c.id = cm.id_clinica
             WHERE cm.id_medicos = '$id_medico'
             AND cm.situacao = 1";

$res_clinicas = $conn->query($sql_clin);

$clinica_escolhida = $_GET['clinica'] ?? "";

$sql_horarios = "
    SELECT 
        MIN(a.hora) AS inicio,
        MAX(a.hora) AS fim,
        a.id_clin_med,
        c.nome AS clinica
    FROM agendados a
    JOIN clin_med cm ON cm.id = a.id_clin_med
    JOIN clinicas_ c ON c.id = cm.id_clinica
    WHERE cm.id_medicos = '$id_medico'
    AND a.id_paciente = 0
    GROUP BY a.id_clin_med, c.nome
";

if ($clinica_escolhida !== "") {
    $sql_horarios = "
        SELECT 
            MIN(a.hora) AS inicio,
            MAX(a.hora) AS fim,
            a.id_clin_med,
            c.nome AS clinica
        FROM agendados a
        JOIN clin_med cm ON cm.id = a.id_clin_med
        JOIN clinicas_ c ON c.id = cm.id_clinica
        WHERE cm.id_medicos = '$id_medico'
        AND a.id_clin_med = '$clinica_escolhida'
        AND a.id_paciente = 0
        GROUP BY a.id_clin_med, c.nome
    ";
}

$sql_horarios .= " ORDER BY clinica ASC, inicio ASC";

$res_horarios = $conn->query($sql_horarios);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Horário de Trabalho</title>
</head>
<body>

<div class="container-box">

<h2>Definir horário de trabalho</h2>

<form action="salvar_horario_medico.php" method="POST">

    <label>Selecione a clínica:</label><br>
    <select name="id_clin_med" required>
        <option value="">Selecione...</option>

        <?php while($row = $res_clinicas->fetch_assoc()) { ?>
            <option value="<?= $row['id_clin_med'] ?>">
                <?= $row['nome'] ?>
            </option>
        <?php } ?>

    </select>

    <br><br>

    <label>Início:</label>
    <input type="time" name="inicio" required><br><br>

    <label>Fim:</label>
    <input type="time" name="fim" required><br><br>

    <button type="submit" class="btn-salvar">Salvar horário</button>
</div>
</form>

<br><br><hr><br>

<h2 class="subtitulo">Períodos cadastrados</h2>

<form method="GET">
    <label>Filtrar por clínica:</label>
    <select name="clinica" onchange="this.form.submit()">
        <option value="">Todas</option>
        <?php
        $res_clinicas2 = $conn->query($sql_clin);
        while ($c = $res_clinicas2->fetch_assoc()) {
            $sel = ($c['id_clin_med'] == $clinica_escolhida) ? "selected" : "";
            echo "<option value='{$c['id_clin_med']}' $sel>{$c['nome']}</option>";
        }
        ?>
    </select>
</form>

<br>

<?php if ($res_horarios->num_rows > 0) { ?>

<!-- 🔥 ENVELOPE QUE FAZ O SCROLL FUNCIONAR -->
<div class="table-wrap">
<table border="1" cellpadding="6" class="tabela">
    <tr>
        <th>Clínica</th>
        <th>Horário</th>
        <th>Ação</th>
    </tr>

    <?php while ($h = $res_horarios->fetch_assoc()) { ?>
        <tr>
            <td><?= $h['clinica'] ?></td>
            <td><?= substr($h['inicio'],0,5) ?> - <?= substr($h['fim'],0,5) ?></td>
            <td>
                <a class="btn-excluir"
                   href="excluir_periodo.php?clinmed=<?= $h['id_clin_med'] ?>&inicio=<?= $h['inicio'] ?>&fim=<?= $h['fim'] ?>"
                   onclick="return confirm('Excluir todo o período?')">
                   Excluir
                </a>
            </td>
        </tr>
    <?php } ?>
</table>
</div>

<?php } else { ?>
    <p class="no-results">Nenhum horário cadastrado.</p>
<?php } ?>
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

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}
header {
  background-color: #0077cc;
  padding: 12px 0;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

body {
  font-family: "Arial", sans-serif;
  background-color: #f5f9ff;
  color: #333;
  line-height: 1.6;
  overflow-x: hidden;
}

.tabs {
  list-style: none;
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 25px;
  margin: 0;
}

.tabs li a {
  color: white;
  text-decoration: none;
  font-weight: bold;
  padding: 10px 18px;
  border-radius: 6px;
  transition: background 0.3s ease;
}

.tabs li a:hover,
.tabs li a.active {
  background-color: #005fa3;
}

.container-box h2 {
    text-align: center;
    font-size: 20px;
    color: #2b5fad;
    margin-bottom: 20px;
}

.subtitulo {
    text-align: center;
    font-size: 18px;
    margin-top: 10px;
    margin-bottom: 10px;
}

label {
    font-size: 14px;
    font-weight: bold;
    color: #444;
}

.input-field,
select,
input[type="time"] {
    width: 100%;
    height: 38px;
    padding: 6px 10px;
    font-size: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-top: 5px;
    margin-bottom: 15px;
    outline: none;
    transition: border 0.2s ease;
}

.input-field:focus,
select:focus,
input[type="time"]:focus {
    border: 1px solid #2b5fad;
}

.btn-salvar {
    width: 100%;
    background: #2b5fad;
    color: white;
    border: none;
    padding: 10px 0;
    font-size: 15px;
    border-radius: 5px;
    margin-top: 5px;
    cursor: pointer;
    transition: background 0.2s ease;
}

.btn-salvar:hover {
    background: #1e4680;
}

.no-results {
    text-align: center;
    margin-top: 20px;
    color: #888;
}

.container-box {
    width: 480px;
    max-width: 95%;
    background: #ffffff;
    margin: 30px auto;
    padding: 30px 40px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.08);
    font-family: Arial, sans-serif;
}

/* 🔥 Wrapper que ativa scroll */
.table-wrap {
    width: 100%;
    overflow-x: auto;    
    -webkit-overflow-scrolling: touch;
    margin-top: 10px;
}

/* Mantém o visual da tabela igual */
.tabela {
    min-width: 640px;
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.tabela th {
    background: #2b5fad;
    color: white;
    padding: 8px;
    border: 1px solid #ddd;
}

.tabela td {
    background: #f6f6f6;
    border: 1px solid #ddd;
    padding: 8px;
}


.btn-excluir {
    color: #d9534f;
    text-decoration: none;
    font-weight: bold;
}

.btn-excluir:hover {
    text-decoration: underline;
}
</style>

</body>
</html>
