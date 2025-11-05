<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/cadastrarmedico.css">
    <link rel="icon" href="clinichelpicon.png">
    <title>Cadastro de Médico - Clinic Help</title>
<?php
include("menu.php");
?>
</head>

<body>
  

    <div class="container">
        <h2>Cadastro</h2>
        <button class="btn-tab">Médico</button>

        <form>
            <input type="text" placeholder="Nome do médico" required>
            <input type="email" placeholder="E-mail" required>
            <input type="password" placeholder="Senha" required>
            <input type="tel" placeholder="Telefone" required>
            <input type="text" placeholder="CPF" required>
            <input type="text" placeholder="CRM" required>

            <label for="esp">Especialização:</label>
            <select id="esp" required>
        <option value="">Selecione</option>
        <option value="Cardiologia">Cardiologia</option>
        <option value="Clinico Geral">Clínico Geral</option>
        <option value="Endocrinologia">Endocrinologia</option>
        <option value="Dermatologia">Dermatologia</option>
        <option value="Gastroenterologia">Gastroenterologia</option>
        <option value="Ginecologia">Ginecologia</option>
        <option value="Infectologia">Infectologia</option>
        <option value="Nefrologia">Nefrologia</option>
        <option value="Neurologia">Neurologia</option>
        <option value="Patologia">Patologia</option>
        <option value="Pediatria">Pediatria</option>
        <option value="Pneumologia">Pneumologia</option>
        <option value="Obstetrícia">Obstetrícia</option>
        <option value="Odontologia">Odontologia</option>
        <option value="Oftalmologia">Oftalmologia</option>
        <option value="Oncologia">Oncologia</option>
        <option value="Ortopedia">Ortopedia</option>
        <option value="Otorrinolaringologia">Otorrinolaringologia</option>
        <option value="Radioterapia">Radioterapia</option>
        <option value="Reumatologia">Reumatologia</option>
        <option value="Urologia">Urologia</option>
        
      </select>

            <button type="submit" class="btn-submit">Cadastrar médico</button>
        </form>
    </div>
    
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