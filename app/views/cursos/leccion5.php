<link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/leccion_teorica.css') ?>">

<div class="clase-teorica-body">
    
    <div class="banner-bienvenida-clase">
        <p class="texto-bienvenida-clase">
            ¡Enhorabuena por avanzar! Hasta ahora has aprendido a hacer clic y a mover objetos uno a uno. En esta clase aprenderás la **selección múltiple**, una técnica para agrupar y mover **varios elementos al mismo tiempo**.
        </p>
    </div>

    <h3 class="titulo-seccion-clase">
        ¿Cómo seleccionar varios objetos a la vez?
    </h3>

  <div class="grid-comparativo-elementos">
        
        <div class="tarjeta-elemento-comparativa">
            <div>
                <h4 class="titulo-elemento-tarjeta">1. Hacer clic para marcar</h4>
                <p class="descripcion-elemento-tarjeta">
                    Haz un clic sobre cada objeto que quieras incluir. Verás cómo aparece un borde resaltado alrededor de cada uno.
                </p>
            </div>
            <div class="wrapper-imagen-tarjeta" style="display: flex; justify-content: center; align-items: center;">
                <div class="icon-visual" style="font-size: 4rem; border: 3px solid #3498db; border-radius: 12px; padding: 10px; background: #f8fafc; line-height: 1;">📁</div>
            </div>
        </div>

        <div class="flecha-separador-flujo">➔</div>

        <div class="tarjeta-elemento-comparativa">
            <div>
                <h4 class="titulo-elemento-tarjeta">2. Mover a la papelera</h4>
                <p class="descripcion-elemento-tarjeta">
                    Una vez seleccionados todos los que quieras, arrástralos para depositarlos todos juntos de un solo golpe en la papelera.
                </p>
            </div>
            <div class="wrapper-imagen-tarjeta" style="display: flex; justify-content: center; align-items: center;">
                <div class="icon-visual" style="font-size: 5rem; line-height: 1;">🗑️</div>
            </div>
        </div>

    </div>

    <div class="caja-reglas-oro">
        <h3 class="titulo-reglas-oro">
            <span>💡</span> Tres trucos para seleccionar rápido:
        </h3>
        
        <ul class="lista-reglas-oro">
            <li class="item-regla-oro">
                <strong>Comprueba el borde:</strong> Asegúrate de que el objeto cambia de aspecto o muestra un recuadro antes de ir a la papelera.
            </li>
            <li class="item-regla-oro">
                <strong>Puedes desmarcar:</strong> Si te equivocas de objeto, vuelve a hacer clic fuera de él para quitarle la selección.
            </li>
            <li class="item-regla-oro ultimo-item">
                <strong>Ahorro de tiempo:</strong> Mover en grupo te evitará tener que ir y volver varias veces con el ratón.
            </li>
        </ul>
    </div>

    <div class="footer-accion-clase">
        <p class="texto-pre-accion">¿Todo claro? ¡Es hora de ponerlo en práctica!</p>
        <button onclick="cambiarSubSeccion('practica')" class="btn-navegar-practica-juego">
            🎮 IR A PROBAR EL JUEGO ➔
        </button>
    </div>

</div>

<script src="<?= base_url('js/accesibilidad.js') ?>"></script>