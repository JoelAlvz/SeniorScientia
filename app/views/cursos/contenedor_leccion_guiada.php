<link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/cursos_guiados.css') ?>">

<div class="leccion-guiada-wrapper">
    
    <div class="leccion-header">
        <a href="<?= base_url('cursos') ?>" class="btn-volver-menu">
            VOLVER AL MENÚ
        </a>

        <div class="header-info-centro">
            <span class="tag-subtitulo-curso">Curso Guiado Autónomo</span>
            <h2 class="titulo-leccion-actual"><?= htmlspecialchars($secuencia['titulo']) ?></h2>
        </div>

        <button id="btn-avanzar-fase-guiada" class="btn-avanzar-fase" data-secuencia="<?= $secuencia['id_secuencia'] ?>">
            <?= $siguiente ? 'SIGUIENTE CLASE ' : ' TERMINAR CURSO' ?>
        </button>
    </div>

    <div class="tabs-navegacion-container">
        <button id="tab-teoria" onclick="cambiarSubSeccion('teoria')" class="tab-control-btn" style="border: 3px solid #007bff; border-bottom: none; border-radius: 12px 12px 0 0; background: white; color: #007bff;">
            📖 1. Leer Explicación
        </button>
        <button id="tab-practica" onclick="cambiarSubSeccion('practica')" class="tab-control-btn" style="border: 3px solid #cbd5e1; border-bottom: none; border-radius: 12px 12px 0 0; background: #f1f5f9; color: #64748b;">
            🎮 2. Probar el Juego (Opcional)
        </button>
    </div>

    <div class="panel-leccion-contenido">
        
        <div id="panel-teoria" style="display: block;">
            <?php 
                $vista = $secuencia['vista_explicativa'];
                include __DIR__ . "/../../views/cursos/" . $vista . ".php"; 
            ?>
        </div>

        <div id="panel-practica" class="panel-practica-wrapper" style="display: none;">
         
            
            <?php if ((int)$secuencia['id_secuencia'] === 1): ?>
                <iframe src="<?= base_url('practica/raton?meta=0&origen=clases&actividad=' . $secuencia['id_secuencia']) ?>" class="iframe-juego-adaptado"></iframe>
            <?php elseif ((int)$secuencia['id_secuencia'] === 2): ?>
                <iframe src="<?= base_url('practica/clicks?meta=0&origen=clases&actividad=' . $secuencia['id_secuencia']) ?>" class="iframe-juego-adaptado"></iframe>
            <?php elseif ((int)$secuencia['id_secuencia'] === 3): ?>
                <iframe src="<?= base_url('practica/dobleclick?meta=0&origen=clases&actividad=' . $secuencia['id_secuencia']) ?>" class="iframe-juego-adaptado"></iframe>
            <?php elseif ((int)$secuencia['id_secuencia'] === 4): ?>
                <iframe src="<?= base_url('practica/arrastrar?meta=0&origen=clases&actividad=' . $secuencia['id_secuencia']) ?>" class="iframe-juego-adaptado"></iframe>
            <?php elseif ((int)$secuencia['id_secuencia'] === 8): ?>
                <iframe src="<?= base_url('practica/juego_seleccion?meta=5&origen=clases&actividad=' . $secuencia['id_secuencia']) ?>" class="iframe-juego-adaptado"></iframe>
            <?php elseif ((int)$secuencia['id_secuencia'] === 9): ?>
                <iframe src="<?= base_url('practica/juego_rueda?meta=3&origen=clases&actividad=' . $secuencia['id_secuencia']) ?>" class="iframe-juego-adaptado"></iframe>
            <?php elseif ((int)$secuencia['id_secuencia'] === 5): ?>
                <iframe src="<?= base_url('practica/teclado?meta=10&tipo=puntos&origen=clases&id_juego=2&actividad=' . $secuencia['id_secuencia']) ?>" class="iframe-juego-adaptado recortado"></iframe>
            <?php else: ?>
                <iframe src="<?= base_url('practica/frases?meta=3&tipo=frases&origen=clases&id_juego=6&actividad=' . $secuencia['id_secuencia']) ?>" class="iframe-juego-adaptado recortado"></iframe>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
document.getElementById('btn-avanzar-fase-guiada').addEventListener('click', function() {
    const idSecuencia = this.getAttribute('data-secuencia');
    const baseUrl = '<?= base_url() ?>';
    
    this.disabled = true;
    this.innerText = "Abriendo siguiente nivel...";

    const params = new URLSearchParams();
    params.append('id_secuencia', idSecuencia);

    fetch(baseUrl + 'cursos/guardar-progreso', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params.toString()
    })
    .then(() => {
        window.location.href = baseUrl + 'cursos';
    })
    .catch(() => {
        window.location.href = baseUrl + 'cursos';
    });
});
</script>

<script src="<?= base_url('js/vistas/cursos_guiados.js') ?>"></script>
<script src="<?= base_url('js/accesibilidad.js') ?>"></script>