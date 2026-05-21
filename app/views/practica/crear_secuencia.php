<div style="max-width: 900px; margin: 0 auto; padding: 20px; font-family: sans-serif;">
    <h2>Diseñar Secuencia de Aprendizaje </h2>
    <p>Construye un camino a la medida de tus alumnos combinando las piezas como quieras.</p>

    <form action="<?= base_url('practica/guardar-secuencia') ?>" method="POST" enctype="multipart/form-data">
        
        <div style="background: #ffffff; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <div style="margin-bottom: 15px;">
                <label style="font-weight: bold; color: #1a202c; display: block; margin-bottom: 5px;">Título de la Actividad:</label>
                <input type="text" name="titulo" required placeholder="Ej: Curso rápido de manejo del Ratón" style="width: 100%; padding: 12px; font-size: 1.2rem; border-radius: 8px; border: 1px solid #cbd5e1; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 15px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label style="font-weight: bold; color: #1a202c; display: block; margin-bottom: 5px;">¿Para quién es?</label>
                    <select name="id_alumno" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 1rem;">
                        <option value="">🌍 Todos los alumnos (Pública)</option>
                        <?php foreach($misAlumnos as $al): ?>
                            <option value="<?= $al['id_usuario'] ?>">👤 Solo para: <?= htmlspecialchars($al['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="font-weight: bold; color: #1a202c; display: block; margin-bottom: 5px;">Área de competencia:</label>
                    <select name="categoria" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 1rem;">
                        <option value="raton">🖱️ Destreza con el Ratón</option>
                        <option value="teclado">⌨️ Mecanografía y Teclado</option>
                        <option value="internet">🌐 Navegación General</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="contenedor-pasos">
            </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 25px;">
            <button type="button" onclick="agregarPaso('texto')" style="background: #f1f5f9; color: #334155; border: 2px dashed #cbd5e1; padding: 15px; border-radius: 8px; font-weight: bold; cursor: pointer;">
                ➕ Añadir Explicación Escrita
            </button>
            <button type="button" onclick="agregarPaso('multimedia')" style="background: #eff6ff; color: #2563eb; border: 2px dashed #bfdbfe; padding: 15px; border-radius: 8px; font-weight: bold; cursor: pointer;">
                ➕ Añadir Vídeo o Imagen
            </button>
            <button type="button" onclick="agregarPaso('juego')" style="background: #f0fdf4; color: #16a34a; border: 2px dashed #bbf7d0; padding: 15px; border-radius: 8px; font-weight: bold; cursor: pointer;">
                ➕ Añadir Juego Práctico
            </button>
        </div>

        <button type="submit" style="background: #2563eb; color: white; padding: 18px; width: 100%; border: none; border-radius: 10px; font-size: 1.25rem; font-weight: bold; cursor: pointer; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);">
            💾 GUARDAR Y ENVIAR RUTA COMPLETA
        </button>
    </form>
</div>

<script>
let contadorPasos = 0;

const juegosDisponibles = <?= json_encode($juegosDisponibles) ?>;

function agregarPaso(tipo) {
    contadorPasos++;
    const contenedor = document.getElementById('contenedor-pasos');
    
    const divPaso = document.createElement('div');
    divPaso.id = `paso_bloque_${contadorPasos}`;
    
    let colorBorde = '#64748b';
    let tituloPaso = `PASO: Explicación Escrita`;
    
    if(tipo === 'multimedia') { colorBorde = '#007bff'; tituloPaso = `PASO: Contenido Audiovisual`; }
    if(tipo === 'juego') { colorBorde = '#28a745'; tituloPaso = `PASO: Práctica con Videojuego`; }

    let htmlInterno = `
        <div style="border: 2px solid ${colorBorde}; background: #f8fafc; padding: 20px; border-radius: 12px; margin-bottom: 25px; position: relative;">
            <input type="hidden" name="pasos[${contadorPasos}][tipo]" value="${tipo}">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <span style="background: ${colorBorde}; color: white; padding: 4px 12px; border-radius: 6px; font-weight: bold; font-size: 0.85rem;">
                    ${tituloPaso}
                </span>
                <button type="button" onclick="eliminarPaso(${contadorPasos})" style="background: #ef4444; color: white; border: none; padding: 5px 10px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; font-weight: bold;">
                    ❌ Quitar paso
                </button>
            </div>
    `;

    if (tipo === 'texto') {
        htmlInterno += `
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Escribe el texto informativo:</label>
            <textarea name="pasos[${contadorPasos}][texto]" rows="4" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e1; box-sizing: border-box;" placeholder="Escribe las instrucciones detalladas aquí..." required></textarea>
        `;
    } 
    else if (tipo === 'multimedia') {
        htmlInterno += `
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Texto de introducción o contexto:</label>
            <textarea name="pasos[${contadorPasos}][texto]" rows="2" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e1; box-sizing: border-box; margin-bottom: 15px;" placeholder="Mira este vídeo/imagen antes de continuar..."></textarea>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div style="background: white; padding: 10px; border: 1px dashed #cbd5e1; border-radius: 6px;">
                    <label style="font-weight: bold; color: #007bff;">📷 Opción A: Subir imagen</label>
                    <input type="file" name="imagen_paso_${contadorPasos}" accept="image/*" style="margin-top: 5px; width: 100%;">
                </div>
                <div style="background: white; padding: 10px; border: 1px dashed #ff0000; border-radius: 6px;">
                    <label style="font-weight: bold; color: #ff0000;">📺 Opción B: Pegar enlace de YouTube</label>
                    <input type="url" name="pasos[${contadorPasos}][video_url]" placeholder="https://www.youtube.com/watch?v=..." style="width: 100%; padding: 6px; margin-top: 5px; border-radius: 4px; border: 1px solid #cbd5e1; box-sizing: border-box;">
                </div>
            </div>
        `;
    } 
    else if (tipo === 'juego') {
        let opcionesJuegos = '';
        juegosDisponibles.forEach(j => {
            opcionesJuegos += `<option value="${j.id_juego}">${j.titulo}</option>`;
        });

        htmlInterno += `
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Elige el juego para esta etapa:</label>
            <select name="pasos[${contadorPasos}][id_juego]" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e1; margin-bottom: 15px;">
                ${opcionesJuegos}
            </select>
            
            <div style="background: white; padding: 12px; border-radius: 6px; border: 1px solid #cbd5e1; display: flex; align-items: center; gap: 15px;">
                <label style="font-weight: bold;">Condición para avanzar:</label>
                <select name="pasos[${contadorPasos}][tipo_meta]" style="padding: 6px; border-radius: 4px;">
                    <option value="puntos">Conseguir estos puntos:</option>
                    <option value="segundos">Jugar estos segundos:</option>
                    <option value="partidas">Terminar este número de partidas:</option>
                </select>
                <input type="number" name="pasos[${contadorPasos}][meta_valor]" value="10" style="width: 60px; padding: 6px; text-align: center; font-weight: bold;">
            </div>
        `;
    }

    htmlInterno += `</div>`;
    divPaso.innerHTML = htmlInterno;
    contenedor.appendChild(divPaso);
}

function eliminarPaso(id) {
    const elemento = document.getElementById(`paso_bloque_${id}`);
    if (elemento) elemento.remove();
}

// Inicializamos la pantalla con un paso de texto automático para guiar al tutor
window.onload = function() {
    agregarPaso('texto');
};
</script>