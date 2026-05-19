<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<h2 style="text-align: center; font-size: 2.5rem;">Zona de Juegos y Práctica</h2>

<div class="contenedor-practicas" style="padding: 20px;">
    <?php foreach ($categorias as $nombreCat => $listaJuegos): ?>
        <?php if (!empty($listaJuegos)): ?>
            <section style="margin-bottom: 40px;">
                <h3 style="text-transform: capitalize; border-bottom: 3px solid #007bff; display: inline-block;">
                    Juegos de <?= $nombreCat ?>
                </h3>
                
                <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
                    <?php foreach ($listaJuegos as $juego): ?>
                        <div class="card-juego" style="width: 250px; border: 2px solid #ddd; border-radius: 15px; padding: 15px; text-align: center; background: white;">
                            <img src="<?= base_url($juego['imagen_url'] ?: 'img/default_juego.png') ?>" style="width: 100%; border-radius: 10px;">
                            <h4 style="margin: 15px 0 10px;"><?= $juego['titulo'] ?></h4>
                            <p style="font-size: 0.9rem; color: #666;"><?= $juego['descripcion'] ?></p>
                            <a href="<?= base_url($juego['ruta_url']) ?>" 
                               style="display: block; background: #28a745; color: white; padding: 10px; text-decoration: none; border-radius: 8px; font-weight: bold;">
                               ¡JUGAR!
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<script src="<?= base_url('js/accesibilidad.js') ?>"></script>