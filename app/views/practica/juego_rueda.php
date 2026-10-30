    <link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/rueda.css') ?>">

   
<body>

    <div class="instruccion-flotante">
        🏢 ¡Gira la rueda dentro del <span style="color: #4ade80; font-size: 1.2rem;">RECUADRO VERDE</span> y apaga las <strong style="color: #fbbf24; font-size: 1.2rem;" id="contador-texto">3</strong> luces encendidas💡<br>
         
    </div>

    <div id="escenario-scroll">
        <div class="contenido-edificio" id="edificio">
            <!-- Las plantas y ventanas se generan por JS -->
        </div>
    </div>

    <div id="mensaje-exito">¡Excelente! Has apagado todas las luces. Reiniciando... 🔄</div>

    
        <div class="juego-footer" style="display: flex; justify-content: center; align-items: center; gap: 20px; margin-top: 15px;">
            <?php if (!isset($_GET['meta'])): ?>
                <a href="javascript:void(0);" id="enlace-volver-menu" style="font-size: 1.2rem; color: #666; text-decoration: underline; padding: 10px; cursor: pointer;">Volver al menú</a>
                
                <button id="btn-terminar-practica" style="background-color: #dc3545; color: white; border: none; padding: 12px 24px; font-size: 1.1rem; font-weight: bold; border-radius: 8px; cursor: pointer;">
                    🛑 Terminar y Guardar Juego
                </button>
            <?php endif; ?>
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

    <script src="<?= base_url('js/vistas/juego_rueda.js') ?>"></script>
    <script src="<?= base_url('js/accesibilidad.js') ?>"></script>

</body>
