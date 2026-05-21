<link rel="stylesheet" href="<?= base_url('css/secuencia_interactiva.css') ?>">

<?php if (!$paso): ?>
    <div class="secuencia-completada-container">
        <span>🏆</span>
        <h1>¡Excelente Trabajo!</h1>
        <p>Has completado con éxito la ruta de aprendizaje: <br><strong><?= htmlspecialchars($secuencia['titulo']) ?></strong></p>
        
        <a href="<?= base_url('dashboard') ?>" class="btn-volver-panel">
            🏠 VOLVER A MI PANEL DE CONTROL
        </a>
    </div>
<?php else: ?>

    <div class="secuencia-layout-main">
        
        <div id="secuencia-cabecera">
            <span class="ruta-texto">Ruta: <strong><?= htmlspecialchars($secuencia['titulo']) ?></strong></span>
            <span class="etapa-badge">
                Etapa <?= $pasoNum ?>
            </span>
        </div>

        <div class="secuencia-contenido-wrapper">
            
            <?php if ($paso['tipo_contenido'] === 'texto'): ?>
                <div class="contenido-tipo-texto">
                    <?= nl2br(htmlspecialchars($paso['texto_instrucciones'])) ?>
                </div>

            <?php elseif ($paso['tipo_contenido'] === 'multimedia'): ?>
                <?php if (!empty($paso['texto_instrucciones'])): ?>
                    <p class="multimedia-instrucciones"><?= htmlspecialchars($paso['texto_instrucciones']) ?></p>
                <?php endif; ?>

                <?php if (!empty($paso['video_url'])): ?>
                    <div class="multimedia-video-container">
                        <iframe src="<?= $paso['video_url'] ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                <?php endif; ?>

                <?php if (!empty($paso['imagen_url'])): ?>
                    <div class="multimedia-imagen-container">
                        <img src="<?= base_url('uploads/' . $paso['imagen_url']) ?>" alt="Instrucción visual">
                    </div>
                <?php endif; ?>

            <?php elseif ($paso['tipo_contenido'] === 'juego'): ?>
                <div class="juego-flex-container">
                    
                    <div class="juego-objetivo-barra">
                        <span class="txt-label">Objetivo:</span>
                        <strong class="txt-meta">
                            <?php 
                            if ($paso['tipo_meta'] === 'puntos') echo "Conseguir " . $paso['meta_valor'] . " puntos.";
                            if ($paso['tipo_meta'] === 'segundos') echo "Jugar durante " . $paso['meta_valor'] . " segundos.";
                            if ($paso['tipo_meta'] === 'partidas') echo "Completar " . $paso['meta_valor'] . " partidas.";
                            ?>
                        </strong>
                    </div>

                    <div id="cartel-meta-exito">
                        <h4>🎉 ¡Excelente, meta cumplida!</h4>
                        <p>Pulsa el gran botón verde de abajo para continuar.</p>
                    </div>

                    <div class="juego-iframe-box">
                        <iframe id="iframe-juego" src="<?= base_url($juego['ruta_url']) ?>?meta=<?= $paso['meta_valor'] ?>&tipo=<?= $paso['tipo_meta'] ?>" width="100%" height="100%"></iframe>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <div id="secuencia-botonera">
            <?php 
            $esJuego = ($paso['tipo_contenido'] === 'juego');
            
            if ($esJuego) {
                $estiloInicial = 'background: #94a3b8; color: #cbd5e1; cursor: not-allowed; opacity: 0.6;';
                $textoInicial = '🔒 DEBES CUMPLIR LA META DEL JUEGO PARA CONTINUAR';
            } else {
                $estiloInicial = 'background: #16a34a; color: white; cursor: pointer; opacity: 1;';
                $textoInicial = $tieneSiguiente ? 'SIGUIENTE PASO ➔' : '✔️ ¡TERMINAR TODA LA LECCIÓN!';
            }

            $textoMetaLograda = $tieneSiguiente ? '🎯 ¡META LOGRADA! PASAR AL SIGUIENTE PASO ➔' : '🏆 ¡LECCIÓN TERMINADA! GUARDAR MI MEDALLA';
            ?>

            <a id="btn-avanzar-secuencia" 
               href="<?= base_url('practica/ver-secuencia/' . $secuencia['id_secuencia'] . '?paso=' . ($pasoNum + 1)) ?>" 
               style="<?= $estiloInicial ?>"
               data-texto-meta="<?= $textoMetaLograda ?>"
               onclick="return validarClickBloqueado(event);">
               <?= $textoInicial ?>
            </a>
        </div>

    </div>
<?php endif; ?>

<script>
    var metaSuperada = <?= ($paso && $paso['tipo_contenido'] === 'juego') ? 'false' : 'true' ?>;
</script>

<script src="<?= base_url('js/vistas/secuencia_interactiva.js') ?>"></script>
