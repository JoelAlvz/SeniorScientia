<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/juegos.css') ?>">

<body data-base-url="<?= base_url() ?>">

    <div class="juego-dobleclick-container">
        
        <div style="flex-shrink: 0; margin-bottom: 10px;">
            <?php if (isset($_GET['meta'])): ?>
                <p id="texto-instruccion" class="instruccion-base">
                    Instrucción: Haz <span class="badge-doble">DOBLE CLIC RÁPIDO</span> para abrir el cofre.
                </p>
            <?php else: ?>
                <h2 class="titulo-base">🎮 El Juego del Cofre Mágico</h2>
                <p id="texto-instruccion" class="instruccion-base">
                    Instrucción: Haz <span class="badge-doble">DOBLE CLIC RÁPIDO</span> para abrir el cofre.
                </p>
            <?php endif; ?>

            <h3 id="marcador-cofres" class="marcador-base">Puntos: 0</h3>
        </div>
        
        <div id="tablero-dobleclick" style="width: 100%; height: 430px; position: relative; overflow: hidden; cursor: default;" class="tablero-estilo">
            <div id="cofre-objetivo" style="position: absolute; width: 120px; height: 120px; cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: all 0.15s ease;">
                <img id="img-cofre" src="<?= base_url('img/cofreCerrado.png') ?>" alt="Cofre cerrado" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
        </div>

        <?php if (!isset($_GET['meta'])): ?>
            <div class="footer-base">
                <a href="javascript:void(0);" id="enlace-volver-menu">Volver al menú</a>
                <button id="btn-terminar-practica">🛑 Terminar y Guardar Juego</button>
            </div>
        <?php endif; ?>
        
    </div>

    <?php if (!isset($_GET['meta'])): ?>
        <div id="modal-confirmar-salida" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;" class="modal-fondo">
            <div class="modal-caja">
                <span>⚠️</span>
                <h3>¿Seguro que quieres salir?</h3>
                <p>Si vuelves al menú ahora mismo, perderás tus puntos actuales y no se guardará tu récord en la clasificación.</p>
                <div class="botones-caja">
                    <button id="btn-modal-cancelar">Seguir Jugando</button>
                    <button id="btn-modal-salir-sin-guardar">Salir sin Guardar</button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="<?= base_url('js/vistas/juego_dobleclick.js') ?>"></script>
        <script src="<?= base_url('js/accesibilidad.js') ?>"></script>
  
</body>