<link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/juegos_teclado.css') ?>">

<body data-base-url="<?= base_url() ?>">

    <div class="juego-container">
        
        <div class="header-box">
            <?php if (isset($_GET['meta'])): ?>
                <p id="texto-instruccion" class="instruccion-text">
                    Instrucción: Copia la frase escribiendo las letras en orden. Usa la <span class="badge-tecla">Barra Larga</span> para los espacios en blanco.
                </p>
            <?php else: ?>
                <h2 class="titulo-juego">✍️ Completar Frases</h2>
                <p id="texto-instruccion" class="instruccion-text">
                    Instrucción: Copia la frase escribiendo las letras en orden. Usa la <span class="badge-tecla">Barra Larga</span> para los espacios en blanco.
                </p>
            <?php endif; ?>

            <h3 id="marcador-frases" class="marcador-text">Frases completadas: 0</h3>
        </div>
        
        <div id="caja-pantalla-frase" class="pantalla-frase">
            <div id="contenedor-letras" class="letras-wrapper"></div>
            <div id="ayuda-tecla" class="ayuda-tecla">Siguiente tecla: [ Letra ]</div>
        </div>

        <div class="juego-footer">
            <?php if (!isset($_GET['meta'])): ?>
                <a href="javascript:void(0);" id="enlace-volver-menu" class="btn-menu">Volver al menú</a>
                <button id="btn-terminar-frases" class="btn-terminar">🛑 Terminar y Guardar Juego</button>
            <?php endif; ?>
        </div>
        
    </div>

    <?php if (!isset($_GET['meta'])): ?>
        <div id="modal-confirmar-salida" class="modal-overlay">
            <div class="modal-content">
                <span>⚠️</span>
                <h3>¿Seguro que quieres salir?</h3>
                <p>Si vuelves al menú ahora mismo, **perderás tus puntos actuales** y no se guardará tu récord en la clasificación.</p>
                <div class="modal-actions">
                    <button id="btn-modal-cancelar" class="btn-secundario">Seguir Jugando</button>
                    <button id="btn-modal-salir-sin-guardar" class="btn-peligro">Salir sin Guardar</button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="<?= base_url('js/accesibilidad.js') ?>"></script>
    <script src="<?= base_url('js/vistas/juego_teclado_frases.js') ?>"></script>
    <script>
        window.addEventListener('DOMContentLoaded', () => { window.focus(); document.body.addEventListener('click', () => { window.focus(); }); });
    </script>
</body>