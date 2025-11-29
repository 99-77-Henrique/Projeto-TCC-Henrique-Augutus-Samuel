<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Início - Clinic Help</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="icon" href="../image/clinichelpicon.png">
</head>
<body>
  <?php
include("menu.php");
?>
  <main>
  <div class="image-container">
    <img src="../image/clinichelplogo-removebg-preview.png" alt="Clinic Help Logo" class="logo">
    
  </div>

  <div class="carousel-texto">
    <p><b>Conectamos você às melhores clínicas com agilidade, confiança e tecnologia. Agende sua consulta em poucos cliques!</b></p>
  </div>

  <div class="carousel">
    <div class="carousel-slide fade">
      <img src="../image/clinica1.jpg" alt="Imagem 1">
    </div>
    <div class="carousel-slide fade">
      <img src="../image/clinica2.jpg" alt="Imagem 2">
    </div>
    <div class="carousel-slide fade">
      <img src="../image/clinica3.jpg" alt="Imagem 3">
    </div>
  </div>
</main>

<script>
  let index = 0;
  const slides = document.querySelectorAll('.carousel-slide');

  function showSlide() {
    slides.forEach((slide, i) => {
      slide.style.display = (i === index) ? 'block' : 'none';
    });
    index = (index + 1) % slides.length;
    setTimeout(showSlide, 3000); 
  }

  window.onload = showSlide;
</script>

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
