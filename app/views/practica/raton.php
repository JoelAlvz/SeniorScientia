<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/raton.css') ?>">

<body data-base-url="<?= base_url() ?>">

    <div class="juego-raton-container">
        
        <div style="flex-shrink: 0;">
            <h2 id="titulo-raton-juego">¡Vamos a practicar con el ratón!</h2>
            <p id="texto-instruccion" class="instruccion-texto">Intenta tocar el <strong>círculo azul</strong> con la flecha de tu ratón.</p>
            
            <?php if(isset($_GET['meta'])): ?>
                <p id="mensaje-meta-tutor" class="meta-tutor-texto">
                    Meta del tutor: ¡Llega a <?= htmlspecialchars($_GET['meta']) ?> puntos para continuar!
                </p>
            <?php endif; ?>

            <h3 id="puntos" class="marcador-puntos">Puntos: 0</h3>
        </div>
        
        <div id="zona-juego">
            <div id="objetivo">¡TÓCAME!</div>
        </div>

        <div class="juego-raton-footer">
            <a href="<?= base_url('dashboard') ?>" id="enlace-volver-menu">Volver al menú</a>
        </div>
        
    </div>

    <script src="<?= base_url('js/vistas/juego_raton.js') ?>"></script>
    <script src="<?= base_url('js/accesibilidad.js') ?>"></script>

</body>