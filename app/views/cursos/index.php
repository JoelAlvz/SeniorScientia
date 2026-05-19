<!-- app/views/cursos/index.php -->
<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">

<div class="contenedor-cursos" style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h2 style="font-size: 2rem; text-align: center; color: #333;">Mi Camino de Aprendizaje</h2>
    <p style="text-align: center; font-size: 1.2rem; color: #666; margin-bottom: 40px;">
        Sigue los pasos en orden para convertirte en un experto con tu ordenador.
    </p>

    <!-- FASE 1 -->
    <div class="fase-bloque" style="margin-bottom: 50px;">
        <h3 style="background: #0f0f0e; padding: 15px; border-radius: 10px; display: inline-block;">Fase 1: El Despertar Digital</h3>
        
        <div class="lista-lecciones" style="margin-top: 20px;">
            
            <!-- LECCIÓN 1 -->
            <div class="leccion-card" style="background: white; border: 3px solid #007bff; border-radius: 20px; padding: 25px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="background: #007bff; color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold;">1</div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.4rem;">El Ratón y la Flecha</h4>
                        <p style="margin: 5px 0 0; color: #555;">Aprende a moverte por la pantalla.</p>
                    </div>
                </div>
                <!-- Botón de estado -->
                <a href="<?= base_url('cursos/leccion/1') ?>" style="background: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 12px; font-weight: bold; font-size: 1.1rem;">EMPEZAR</a>
            </div>

            <!-- LECCIÓN 2 (Bloqueada por ahora) -->
            <div class="leccion-card" style="background: #f8f9fa; border: 2px dashed #ccc; border-radius: 20px; padding: 25px; display: flex; align-items: center; justify-content: space-between; opacity: 0.7;">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="background: #6c757d; color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold;">2</div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.4rem;">Hacer Clic</h4>
                        <p style="margin: 5px 0 0; color: #555;">Cómo seleccionar cosas con el botón.</p>
                    </div>
                </div>
                <span style="color: #6c757d; font-weight: bold;">🔒 BLOQUEADO</span>
            </div>

        </div>
    </div>
</div>

<script src="<?= base_url('js/accesibilidad.js') ?>"></script>