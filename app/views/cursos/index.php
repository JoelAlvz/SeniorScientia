<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">

<div class="contenedor-cursos" style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h2 style="font-size: 2rem; text-align: center; color: #333;">Mi Camino de Aprendizaje</h2>
    <p style="text-align: center; font-size: 1.2rem; color: #666; margin-bottom: 40px;">
        Sigue los pasos en orden para convertirte en un experto con tu ordenador.
    </p>

    <?php 
    $ultimaSecuenciaCompletada = true; 
    ?>

    <?php foreach ($modulos as $modulo): ?>
        <div class="fase-bloque" style="margin-bottom: 50px;">
            <h3 style="background: #0f0f0e; color: white; padding: 15px; border-radius: 10px; display: inline-block;">
                <?= htmlspecialchars($modulo['titulo']) ?>
            </h3>
            
            <div class="lista-lecciones" style="margin-top: 20px;">
                
                <?php foreach ($modulo['secuencias'] as $index => $secuencia): ?>
                    <?php 
                    $estaCompletada = in_array($secuencia['id_secuencia'], $completadas);
                    
                    $estaDesbloqueada = $ultimaSecuenciaCompletada; 
                    ?>

                    <?php if ($estaDesbloqueada): ?>
                        <div class="leccion-card" style="background: white; border: 3px solid #007bff; border-radius: 20px; padding: 25px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <div style="background: #007bff; color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold;">
                                    <?= $index + 1 ?>
                                </div>
                                <div>
                                    <h4 style="margin: 0; font-size: 1.4rem;"><?= htmlspecialchars($secuencia['titulo']) ?></h4>
                                    <p style="margin: 5px 0 0; color: #555;"><?= htmlspecialchars($secuencia['descripcion']) ?></p>
                                </div>
                            </div>
                            
                            <?php if ($estaCompletada): ?>
                                <a href="<?= base_url('practica/ver-secuencia/' . $secuencia['id_secuencia']) ?>" style="background: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 12px; font-weight: bold; font-size: 1.1rem;">⭐ REPETIR</a>
                            <?php else: ?>
                                <a href="<?= base_url('practica/ver-secuencia/' . $secuencia['id_secuencia']) ?>" style="background: #007bff; color: white; padding: 15px 30px; text-decoration: none; border-radius: 12px; font-weight: bold; font-size: 1.1rem;">EMPEZAR</a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="leccion-card" style="background: #f8f9fa; border: 2px dashed #ccc; border-radius: 20px; padding: 25px; display: flex; align-items: center; justify-content: space-between; opacity: 0.7; margin-bottom: 20px;">
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <div style="background: #6c757d; color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold;">
                                    <?= $index + 1 ?>
                                </div>
                                <div>
                                    <h4 style="margin: 0; font-size: 1.4rem; color: #888;"><?= htmlspecialchars($secuencia['titulo']) ?></h4>
                                    <p style="margin: 5px 0 0; color: #aaa;"><?= htmlspecialchars($secuencia['descripcion']) ?></p>
                                </div>
                            </div>
                            <span style="color: #6c757d; font-weight: bold;">🔒 BLOQUEADO</span>
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