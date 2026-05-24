<link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/leccion_teorica.css') ?>">

<div class="clase-teorica-body">
    
    <div class="banner-bienvenida-clase">
        <p class="texto-bienvenida-clase">
            ¡Te damos la bienvenida a tu primera clase! Para usar un ordenador, lo primero que necesitamos conocer es el <strong>ratón</strong>. Esta herramienta será como la extensión de tu mano en la pantalla.
        </p>
    </div>

    <h3 class="titulo-seccion-clase">
        ¿Cómo se conecta tu mano con la pantalla?
    </h3>

    <div class="grid-comparativo-elementos">
        
        <div class="tarjeta-elemento-comparativa">
            <div>
                <h4 class="titulo-elemento-tarjeta">1. El Ratón Real</h4>
                <p class="descripcion-elemento-tarjeta">
                    Es el objeto físico que apoyas en la mesa y que mueves con tu mano.
                </p>
            </div>
            <div class="wrapper-imagen-tarjeta">
                <img src="<?= base_url('img/ratonPC.jpg') ?>" alt="Foto de un ratón de ordenador apoyado en una mesa" class="imagen-tarjeta-adaptada">
            </div>
        </div>

        <div class="flecha-separador-flujo">➔</div>

        <div class="tarjeta-elemento-comparativa">
            <div>
                <h4 class="titulo-elemento-tarjeta">2. El Puntero o Flecha</h4>
                <p class="descripcion-elemento-tarjeta">
                    Es el dibujo en forma de flecha blanca que imita en la pantalla cada movimiento que haces en la mesa.
                </p>
            </div>
            <div class="wrapper-imagen-tarjeta">
                <img src="<?= base_url('img/cursor.png') ?>" alt="Dibujo claro de una flecha o cursor de pantalla de ordenador" class="imagen-tarjeta-adaptada puntero-ajuste">
            </div>
        </div>

    </div>

    <div class="caja-reglas-oro">
        <h3 class="titulo-reglas-oro">
            <span>💡</span> Tres reglas de oro para empezar:
        </h3>
        
        <ul class="lista-reglas-oro">
            <li class="item-regla-oro">
                <strong>Apoya toda la mano:</strong> Descansa la palma sobre el ratón de forma relajada, sin apretar con fuerza.
            </li>
            <li class="item-regla-oro">
                <strong>No lo levantes de la mesa:</strong> El ratón necesita estar siempre deslizando sobre la superficie física para que la pantalla detecte el movimiento.
            </li>
            <li class="item-regla-oro ultimo-item">
                <strong>Direcciones naturales:</strong> Si mueves tu mano hacia la derecha, la flecha irá a la derecha. Si empujas el ratón hacia adelante, la flecha subirá.
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