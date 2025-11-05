<?php
session_start();
?>
<header>
<ul class="tabs">
    <li><a href="site.php">Início</a></li>
    <li><a href="quemsomos.php">Quem somos</a></li>

    <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
                <?php if ($_SESSION['tipo'] === 'paciente'): ?>
                    <li><a href="Tclinicas.php">Clínicas</a></li>
                    <li><a href="Tagendados.php">Agendados</a></li>
                <?php endif; ?>
        <?php if ($_SESSION['tipo'] === 'clinica'): ?>
            <li><a href="clinicachamamarrmedico.php">Medicos</a></li>
            <li><a href="TagendadosClin.php">Agendados</a></li>
        <?php endif; ?>
        
        <li><a href="contato.php">Contato</a></li>
        <li><a href="sair.php">Sair</a></li>

    <?php else: ?>
        <li><a href="contato.php">Contato</a></li>
        <li><a href="login.php">Login</a></li>
    <?php endif; ?>
</ul>
</header>
