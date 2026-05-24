<link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/camino_aprendizaje.css') ?>">

<div class="contenedor-cursos">
    
    <div class="header-camino-wrapper">
        <h2 class="titulo-camino">Mi Camino de Aprendizaje</h2>
        
        <a href="<?= base_url('dashboard') ?>" class="btn-volver-panel">
             Volver al Panel
        </a>
    </div>

    <p class="descripcion-camino">
        Sigue los pasos en orden para convertirte en un experto con tu ordenador.
    </p>

    <?php 
    $ultimaSecuenciaCompletada = true; 
    ?>

    <?php foreach ($modulos as $modulo): ?>
        <div class="fase-bloque">
            <h3 class="titulo-fase-modulo">
                <?= htmlspecialchars($modulo['titulo']) ?>
            </h3>
            
            <div class="lista-lecciones">
                
                <?php foreach ($modulo['secuencias'] as $index => $secuencia): ?>
                    <?php 
                    $estaCompletada = in_array($secuencia['id_secuencia'], $completadas);
                    $estaDesbloqueada = $ultimaSecuenciaCompletada;
                    ?>

                    <?php if ($estaDesbloqueada): ?>
                        <div class="leccion-card desbloqueada">
                            <div class="leccion-card-info-side">
                                <div class="leccion-card-badge-numero">
                                    <?= $index + 1 ?>
                                </div>
                                <div>
                                    <h4 class="leccion-card-titulo"><?= htmlspecialchars($secuencia['titulo']) ?></h4>
                                    <p class="leccion-card-descripcion"><?= htmlspecialchars($secuencia['descripcion']) ?></p>
                                </div>
                            </div>
                            
                            <?php if ($estaCompletada): ?>
                                <a href="<?= base_url('cursos/ver-leccion/' . $secuencia['id_secuencia']) ?>" class="btn-accion-leccion repetir">REPETIR</a>
                            <?php else: ?>
                                <a href="<?= base_url('cursos/ver-leccion/' . $secuencia['id_secuencia']) ?>" class="btn-accion-leccion empezar">EMPEZAR</a>
                            <?php endif; ?>
                        </div>

                    <?php else: ?>
                        <div class="leccion-card bloqueada">
                            <div class="leccion-card-info-side">
                                <div class="leccion-card-badge-numero">
                                    <?= $index + 1 ?>
                                </div>
                                <div>
                                    <h4 class="leccion-card-titulo"><?= htmlspecialchars($secuencia['titulo']) ?></h4>
                                    <p class="leccion-card-descripcion"><?= htmlspecialchars($secuencia['descripcion']) ?></p>
                                </div>
                            </div>
                            <span class="tag-bloqueado-badge">🔒 BLOQUEADO</span>
                        </div>
                    <?php endif; ?>

                    <?php 
                    $ultimaSecuenciaCompletada = $estaCompletada; 
                    ?>
                <?php endforeach; ?>

            </div>
        </div>
    <?php endforeach; ?>
</div>

<script src="<?= base_url('js/accesibilidad.js') ?>"></script>