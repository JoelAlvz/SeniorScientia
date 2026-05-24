<link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/leccion_teorica.css') ?>">

<div class="clase-teorica-body">
    
    <div class="banner-bienvenida-clase">
        <p class="texto-bienvenida-clase">
            ¡Ya dominas los clics sueltos! Pero para <strong>abrir cosas</strong> en el ordenador (como fotos, carpetas o programas), necesitamos aprender el último secreto del ratón: el <strong>Doble Clic</strong>.
        </p>
    </div>

    <h3 class="titulo-seccion-clase">
        ¿Qué es el Doble Clic?
    </h3>

    <div class="grid-comparativo-elementos">
        
        <div class="columna-texto-explicativo">
            <p>Consiste en pulsar el <strong>botón izquierdo dos veces seguidas muy rápido</strong>.</p>
            <p>Es exactamente igual que cuando el cartero llama a tu puerta haciendo: <strong class="texto-resaltado-grande">¡Toc-Toc!</strong></p>
            
            <div class="caja-aviso-alerta">
                ⚠️ <strong>El error más común:</strong> Mover el ratón mientras haces los clics. Para que funcione, tu mano debe estar <strong>completamente quieta</strong> en la mesa mientras tus dedos pulsan la tecla.
            </div>
        </div>

        <div class="tarjeta-elemento-comparativa ancho-fijo-lateral">
            <h4 class="titulo-elemento-tarjeta">El Ritmo del Éxito</h4>
            <div class="wrapper-imagen-tarjeta">
                <img src="<?= base_url('img/dobleClick.gif') ?>" alt="Esquema que representa dos clics seguidos muy rápidos con ritmo constante" class="imagen-tarjeta-adaptada ritmo-ajuste">
                <span class="texto-badge-exito-imagen">¡Clic-Clic! sin moverte</span>
            </div>
        </div>

    </div>

    <div class="footer-accion-clase">
        <p class="texto-pre-accion">¿Practicamos el ritmo del "Toc-Toc"?</p>
        <button onclick="cambiarSubSeccion('practica')" class="btn-navegar-practica-juego">
            🎮 IR ABRIR COFRES TESORO ➔
        </button>
    </div>

</div>

<script src="<?= base_url('js/accesibilidad.js') ?>"></script>