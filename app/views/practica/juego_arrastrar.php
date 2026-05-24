<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/juegos.css') ?>">
<body data-base-url="<?= base_url() ?>">

    <div class="juego-arrastrar-container">
        
        <div class="header-juego">
            <?php if (isset($_GET['meta'])): ?>
                <p id="texto-instruccion">
                    Instrucción: Deja pulsada la manzana, muévela <span>DENTRO DE LA CESTA</span> y suéltala.
                </p>
            <?php else: ?>
                <h2>🎮 El Juego de la Cesta de Manzanas</h2>
                <p id="texto-instruccion">
                    Instrucción: Deja pulsada la manzana, muévela <span>DENTRO DE LA CESTA</span> y suéltala.
                </p>
            <?php endif; ?>

            <h3 id="marcador-manzanas">Puntos: 0</h3>
        </div>
        
        <div id="tablero-arrastrar">
            
            <div id="manzana-arrastrable" draggable="true">
                <img src="<?= base_url('img/manzana.png') ?>" alt="Manzana roja">
            </div>

            <div id="cesta-destino">
                <img id="img-cesta" src="<?= base_url('img/cestaCerrada.png') ?>" alt="Cesta de mimbre">
                <span id="texto-cesta-estado">¡TRAE AQUÍ! 🧺</span>
            </div>

        </div>

        <?php if (!isset($_GET['meta'])): ?>
            <div class="juego-arrastrar-footer">
                <a href="javascript:void(0);" id="enlace-volver-menu">Volver al menú</a>
                
                <button id="btn-terminar-practica">
                    🛑 Terminar y Guardar Juego
                </button>
            </div>
        <?php endif; ?>
        
    </div>

    <?php if (!isset($_GET['meta'])): ?>
        <div id="modal-confirmar-salida">
            <div class="modal-contenido">
                <span>⚠️</span>
                <h3>¿Seguro que quieres salir?</h3>
                <p>
                    Si vuelves al menú ahora mismo, <strong>perderás tus puntos actuales</strong> y no se guardará tu récord en la clasificación.
                </p>
                <div class="modal-acciones">
                    <button id="btn-modal-cancelar">Seguir Jugando</button>
                    <button id="btn-modal-salir-sin-guardar">Salir sin Guardar</button>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <script src="<?= base_url('js/accesibilidad.js') ?>"></script>
    <script src="<?= base_url('js/vistas/juego_arrastrar.js') ?>"></script>
</body>