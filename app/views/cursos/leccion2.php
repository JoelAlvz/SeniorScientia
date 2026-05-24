<link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/leccion_teorica.css') ?>">

<div class="clase-teorica-body">
    
    <div class="banner-bienvenida-clase">
        <p class="texto-bienvenida-clase">
            ¡Excelente! Ya sabes mover la flecha. Ahora aprenderemos a <strong>pulsar los botones</strong> del ratón. Esto se conoce como hacer <strong>"Clic"</strong> y sirve para darle órdenes al ordenador.
        </p>
    </div>

    <h3 class="titulo-seccion-clase">
        Los dos botones del ratón
    </h3>

    <div class="grid-comparativo-elementos">
        
        <div class="tarjeta-elemento-comparativa">
            <div>
                <h4 class="titulo-elemento-tarjeta">
                    <span class="color-azul-click">🔵</span> Botón Izquierdo (El Principal)
                </h4>
                <p class="descripcion-elemento-tarjeta">
                    Es el botón que pulsarás el <strong>90% de las veces</strong>. Sirve para seleccionar cosas, abrir menús, pulsar botones en la pantalla y confirmar acciones.
                </p>
            </div>
            <div class="wrapper-imagen-tarjeta">
                <img src="<?= base_url('img/clicIzq.png') ?>" alt="Dibujo de un ratón con el botón izquierdo resaltado en azul" class="imagen-tarjeta-adaptada click-ajuste">
            </div>
        </div>

        <div class="tarjeta-elemento-comparativa">
            <div>
                <h4 class="titulo-elemento-tarjeta">
                    <span class="color-rojo-click">🔴</span> Botón Derecho (Opciones)
                </h4>
                <p class="descripcion-elemento-tarjeta">
                    Se pulsa de forma más ocasional. Su única función es desplegar un <strong>menú de opciones ocultas o de ayuda</strong> sobre el objeto que estés apuntando.
                </p>
            </div>
            <div class="wrapper-imagen-tarjeta">
                <img src="<?= base_url('img/clicDer.png') ?>" alt="Dibujo de un ratón con el botón derecho resaltado en rojo" class="imagen-tarjeta-adaptada click-ajuste">
            </div>
        </div>

    </div>

    <div class="caja-reglas-oro">
        <h3 class="titulo-reglas-oro">⚡ El truco del "clic" perfecto:</h3>
        <p class="lista-reglas-oro" style="list-style: none; padding-left: 0;">
            Da un golpe suave, rápido y seco con tu dedo índice o corazón. **No dejes el botón pulsado hacia abajo** de forma prolongada, o el ordenador podría confundirse. ¡Un toque rápido de "bajar y subir" basta!
        </p>
    </div>

    <div class="footer-accion-clase">
        <p class="texto-pre-accion">¿Listo para entrenar tus dedos?</p>
        <button onclick="cambiarSubSeccion('practica')" class="btn-navegar-practica-juego">
            🎮 IR A PROBAR EL JUEGO DEL CLIC ➔
        </button>
    </div>

</div>

<script src="<?= base_url('js/accesibilidad.js') ?>"></script>