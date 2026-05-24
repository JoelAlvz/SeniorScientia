<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/clicks.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/juegos.css') ?>">

<body data-base-url="<?= base_url() ?>">

    <div class="juego-clicks-container">
        
        <div class="header-juego">
            <?php if (isset($_GET['meta'])): ?>
                <p id="texto-instruccion">
                    Instrucción: Haz <span id="tipo-click-requerido">CLIC IZQUIERDO</span> sobre el círculo.
                </p>
            <?php else: ?>
                <h2 id="titulo-clicks-juego">¡Vamos a practicar los clics!</h2>
                <p id="texto-instruccion">
                    Instrucción: Haz <span id="tipo-click-requerido">CLIC IZQUIERDO</span> sobre el círculo.
                </p>
            <?php endif; ?>

            <h3 id="marcador-puntos">Puntos: 0</h3>
        </div>
        
        <div id="zona-juego-clicks">
            <div id="objetivo-click">¡CLIC!</div>
        </div>

        <?php if (!isset($_GET['meta'])): ?>
            <div class="juego-clicks-footer">
                <a href="javascript:void(0);" id="enlace-volver-menu">Volver al menú</a>
                <button id="btn-terminar-practica">🛑 Terminar y Guardar Juego</button>
            </div>
        <?php endif; ?>
        
    </div>

    <?php if (!isset($_GET['meta'])): ?>
        <div id="modal-confirmar-salida">
            <div class="modal-contenido">
                <span>⚠️</span>
                <h3>¿Seguro que quieres salir?</h3>
                <p>Si vuelves al menú ahora mismo, perderás tus puntos actuales y no se guardará tu récord.</p>
                <div class="modal-botones">
                    <button id="btn-modal-cancelar">Seguir Jugando</button>
                    <button id="btn-modal-salir-sin-guardar">Salir sin Guardar</button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="<?= base_url('js/accesibilidad.js') ?>"></script>
    <script src="<?= base_url('js/vistas/juego_clicks.js') ?>"></script>
</body>