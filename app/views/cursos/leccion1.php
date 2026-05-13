<!-- app/views/cursos/leccion1.php -->
<div style="max-width: 700px; margin: 0 auto; padding: 30px; font-family: sans-serif; line-height: 1.6;">
    <h2 style="color: #007bff; text-align: center; font-size: 2rem;">Paso 1: El Ratón y la Flecha</h2>
    
    <div style="background: #fdf6e3; padding: 20px; border-radius: 15px; border-left: 10px solid #ffc107; margin: 20px 0;">
        <p style="font-size: 1.3rem;">
            El <strong>ratón</strong> es la herramienta que usas para mover la <strong>flecha</strong> que ves en la pantalla.
        </p>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <!-- Aquí puedes poner una imagen real de un ratón -->
        <div style="background: #eee; padding: 40px; border-radius: 20px; display: inline-block;">
            <span style="font-size: 5rem;">🖱️</span>
            <p>Este es tu ratón</p>
        </div>
        <span style="font-size: 3rem;"> ➔ </span>
        <div style="background: #eee; padding: 40px; border-radius: 20px; display: inline-block;">
            <span style="font-size: 5rem;">🏹</span>
            <p>Esta es la flecha</p>
        </div>
    </div>

    <div style="font-size: 1.2rem;">
        <h3>¿Cómo se usa?</h3>
        <ul>
            <li>Desliza el ratón sobre la mesa (no lo levantes).</li>
            <li>Si mueves el ratón a la derecha, la flecha va a la derecha.</li>
            <li>Si mueves el ratón hacia adelante, la flecha sube.</li>
        </ul>
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <p style="font-weight: bold;">¿Quieres probarlo tú mismo?</p>
        <a href="<?= base_url('practica/raton') ?>" 
           style="background: #28a745; color: white; padding: 20px 40px; text-decoration: none; font-size: 1.5rem; border-radius: 15px; display: inline-block; box-shadow: 0 5px 0 #1e7e34;">
            ¡SÍ, QUIERO PRACTICAR!
        </a>
    </div>
    
    <p style="text-align: center; margin-top: 20px;">
        <a href="<?= base_url('cursos') ?>" style="color: #666;">Volver atrás</a>
    </p>
</div>