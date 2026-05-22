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

        <div class="juego-raton-footer" style="display: flex; justify-content: center; align-items: center; gap: 20px; margin-top: 15px;">
            <a href="javascript:void(0);" id="enlace-volver-menu" style="font-size: 1.2rem; color: #666; text-decoration: underline; padding: 10px; cursor: pointer;">Volver al menú</a>
            
            <button id="btn-terminar-practica" style="background-color: #dc3545; color: white; border: none; padding: 12px 24px; font-size: 1.1rem; font-weight: bold; border-radius: 8px; cursor: pointer;">
                🛑 Terminar y Guardar Juego
            </button>
        </div>
        
    </div>

    <div id="modal-confirmar-salida" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center; font-family: sans-serif;">
        <div style="background: white; padding: 30px; border-radius: 16px; max-width: 450px; width: 90%; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.3); border: 3px solid #dc3545;">
            <span style="font-size: 3.5rem;">⚠️</span>
            <h3 style="margin: 15px 0 10px; color: #1e293b; font-size: 1.6rem;">¿Seguro que quieres salir?</h3>
            <p style="color: #64748b; font-size: 1.1rem; line-height: 1.5; margin-bottom: 25px;">
                Si vuelves al menú ahora mismo, **perderás tus puntos actuales** y no se guardará tu récord en la clasificación de la clase.
            </p>
            <div style="display: flex; justify-content: center; gap: 15px;">
                <button id="btn-modal-cancelar" style="background: #cbd5e1; color: #334155; border: none; padding: 12px 20px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                    Seguir Jugando
                </button>
                <button id="btn-modal-salir-sin-guardar" style="background: #dc3545; color: white; border: none; padding: 12px 20px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                    Salir sin Guardar
                </button>
            </div>
        </div>
    </div>

    <script src="<?= base_url('js/vistas/juego_raton.js') ?>"></script>
    <script src="<?= base_url('js/accesibilidad.js') ?>"></script>

</body>