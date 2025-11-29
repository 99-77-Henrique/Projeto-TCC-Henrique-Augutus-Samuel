<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Médicos</title>
    <link rel="stylesheet" href="../css/editarmedico.css">
</head>
<body>
 <?php
include("menu.php");
?>

    <main>
        <h1>Editar Médicos</h1>
        <div class="medicos-lista">
            <div class="medico">
                <h2>Dr. Nome do médico</h2>
                <p>Especialidade</p>
                <p>CRM</p>
                <p>Telefone</p>
                <p>Email</p>
                <div class="acoes">
                    <button class="editar">✎</button>
                    <button class="excluir">✖</button>
                </div>
            </div>

            <div class="medico">
                <h2>Dr. Nome do médico</h2>
                <p>Especialidade</p>
                <p>CRM</p>
                <p>Telefone</p>
                <p>Email</p>
                <div class="acoes">
                    <button class="editar">✎</button>
                    <button class="excluir">✖</button>
                </div>
            </div>

            <div class="medico">
                <h2>Dr. Nome do médico</h2>
                <p>Especialidade</p>
                <p>CRM</p>
                <p>Telefone</p>
                <p>Email</p>
                <div class="acoes">
                    <button class="editar">✎</button>
                    <button class="excluir">✖</button>
                </div>
            </div>
        </div>
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
