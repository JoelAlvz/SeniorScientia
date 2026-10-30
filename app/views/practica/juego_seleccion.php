<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/seleccion.css') ?>">

<body data-base-url="<?= base_url() ?>">

    <div class="juego-seleccion-container">
        <div style="flex-shrink: 0;">
            <h2 id="titulo-juego">¡Practicamos la Selección Múltiple!</h2>
            <p id="texto-instruccion" class="instruccion-texto">Haz clic en varias manzanas para seleccionarlas y luego arrástralas o haz clic en la cesta.</p>
            
            <?php if(isset($_GET['meta'])): ?>
                <p id="mensaje-meta-tutor" class="meta-tutor-texto">
                    Meta del tutor: ¡Llega a <?= htmlspecialchars($_GET['meta']) ?> puntos para continuar!
                </p>
            <?php endif; ?>

            <h3 id="puntos" class="marcador-puntos">Puntos: 0</h3>
        </div>
        
        <!-- Zona de Juego -->
        <div id="escenario">
            <div id="selection-box"></div>
            <div id="cesta" class="zona-cesta">

            <div class="icon-visual">🗑️</div>
                <div class="icon-label">Papelera</div>
            </div>
        </div>

      

        <div class="juego-footer" style="display: flex; justify-content: center; align-items: center; gap: 20px; margin-top: 15px;">
            <?php if (!isset($_GET['meta'])): ?>
                <a href="javascript:void(0);" id="enlace-volver-menu" style="font-size: 1.2rem; color: #666; text-decoration: underline; padding: 10px; cursor: pointer;">Volver al menú</a>
                
                <button id="btn-terminar-practica" style="background-color: #dc3545; color: white; border: none; padding: 12px 24px; font-size: 1.1rem; font-weight: bold; border-radius: 8px; cursor: pointer;">
                    🛑 Terminar y Guardar Juego
                </button>
            <?php endif; ?>
        </div>
    </div>

    <div id="modal-confirmar-salida" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center; font-family: sans-serif;">
        <div style="background: white; padding: 30px; border-radius: 16px; max-width: 450px; width: 90%; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.3); border: 3px solid #dc3545;">
            <span style="font-size: 3.5rem;">⚠️</span>
            <h3 style="margin: 15px 0 10px; color: #1e293b; font-size: 1.6rem;">¿Seguro que quieres salir?</h3>
            <p style="color: #64748b; font-size: 1.1rem; line-height: 1.5; margin-bottom: 25px;">
                Si vuelves al menú ahora mismo, perderás tus puntos actuales.
            </p>
            <div style="display: flex; justify-content: center; gap: 15px;">
                <button id="btn-modal-cancelar" style="background: #cbd5e1; color: #334155; border: none; padding: 12px 20px; border-radius: 8px; font-weight: bold; cursor: pointer;">Seguir Jugando</button>
                <button id="btn-modal-salir-sin-guardar" style="background: #dc3545; color: white; border: none; padding: 12px 20px; border-radius: 8px; font-weight: bold; cursor: pointer;">Salir sin Guardar</button>
            </div>
        </div>
    </div>

    <script src="<?= base_url('js/vistas/juego_seleccion.js') ?>"></script>
    <script src="<?= base_url('js/accesibilidad.js') ?>"></script>
</body>