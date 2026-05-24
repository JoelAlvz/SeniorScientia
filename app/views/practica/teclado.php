<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/raton.css') ?>"> 

<body data-base-url="<?= base_url() ?>">

    <div class="juego-raton-container" style="text-align: center;">
        
        <div style="flex-shrink: 0;">
            <?php if (isset($_GET['meta'])): ?>
                <!-- Si se juega desde una secuencia, SOLO aparece este texto -->
                <p id="texto-instruccion" class="instruccion-texto" style="font-size: 1.6rem; margin-top: 20px; font-weight: bold; color: #1e293b;">
                    Busca en tu teclado físico la **letra grande** que aparece abajo y púlsala.
                </p>
            <?php else: ?>
                <h2 id="titulo-teclado-juego">¡Vamos a practicar con el teclado!</h2>
                <p id="texto-instruccion" class="instruccion-texto" style="font-size: 1.4rem;">
                    Busca en tu teclado físico la **letra grande** que aparece abajo y púlsala.
                </p>
            <?php endif; ?>

            <h3 id="puntos" class="marcador-puntos">Puntos: 0</h3>
        </div>
        
        <div id="zona-juego" style="display: flex; justify-content: center; align-items: center; background-color: #f8fafc; border: 3px dashed #cbd5e1; min-height: 350px; border-radius: 16px;">
            <div id="cuadro-letra" style="font-size: 8rem; font-weight: bold; color: #1e293b; background: white; width: 180px; height: 180px; display: flex; justify-content: center; align-items: center; border-radius: 24px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border: 4px solid #007bff; transition: all 0.15s ease;">
                A
            </div>
        </div>

        <?php if (!isset($_GET['meta'])): ?>
            <!-- Estos botones SOLO aparecen si NO es parte de una secuencia tutorizada -->
            <div class="juego-raton-footer" style="display: flex; justify-content: center; align-items: center; gap: 20px; margin-top: 15px;">
                <a href="javascript:void(0);" id="enlace-volver-menu" style="font-size: 1.2rem; color: #666; text-decoration: underline; padding: 10px; cursor: pointer;">Volver al menú</a>
                
                <button id="btn-terminar-practica" style="background-color: #dc3545; color: white; border: none; padding: 12px 24px; font-size: 1.1rem; font-weight: bold; border-radius: 8px; cursor: pointer;">
                    🛑 Terminar y Guardar Juego
                </button>
            </div>
        <?php endif; ?>
        
    </div>

    <?php if (!isset($_GET['meta'])): ?>
        <div id="modal-confirmar-salida" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center; font-family: sans-serif;">
            <div style="background: white; padding: 30px; border-radius: 16px; max-width: 450px; width: 90%; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.3); border: 3px solid #dc3545;">
                <span style="font-size: 3.5rem;">⚠️</span>
                <h3 style="margin: 15px 0 10px; color: #1e293b; font-size: 1.6rem;">¿Seguro que quieres salir?</h3>
                <p style="color: #64748b; font-size: 1.1rem; line-height: 1.5; margin-bottom: 25px;">
                    Si vuelves al menú ahora mismo, **perderás tus puntos actuales** y no se guardará tu récord en la clasificación.
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
    <?php endif; ?>

    <script src="<?= base_url('js/vistas/juego_teclado.js') ?>"></script>

    <script src="<?= base_url('js/accesibilidad.js') ?>"></script>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            window.focus();
            
            document.body.addEventListener('click', () => {
                window.focus();
            });
        });
    </script>
</body>

