let contadorPasos = 0;

function agregarPaso(tipo) {
    contadorPasos++;
    const contenedor = document.getElementById('contenedor-pasos');
    
    if (!contenedor) return;
    
    const divPaso = document.createElement('div');
    divPaso.id = `paso_bloque_${contadorPasos}`;
    
    let tituloPaso = `PASO: Explicación Escrita`;
    if (tipo === 'multimedia') { tituloPaso = `PASO: Contenido Audiovisual`; }
    if (tipo === 'juego') { tituloPaso = `PASO: Práctica con Videojuego`; }

    let htmlInterno = `
        <div class="bloque-paso-dinamico" data-tipo="${tipo}">
            <input type="hidden" name="pasos[${contadorPasos}][tipo]" value="${tipo}">
            
            <div class="cabecera-paso-dinamico">
                <span class="etiqueta-indicador-paso">
                    ${tituloPaso}
                </span>
                <button type="button" onclick="eliminarPaso(${contadorPasos})" class="btn-eliminar-paso">
                    ❌ Quitar paso
                </button>
            </div>
    `;

    if (tipo === 'texto') {
        htmlInterno += `
            <label class="label-bold display-block-margin">Escribe el texto informativo:</label>
            <textarea name="pasos[${contadorPasos}][texto]" rows="4" class="textarea-paso" placeholder="Escribe las instrucciones detalladas aquí..." required></textarea>
        `;
    } 
    else if (tipo === 'multimedia') {
        htmlInterno += `
            <label class="label-bold display-block-margin">Texto de introducción o contexto:</label>
            <textarea name="pasos[${contadorPasos}][texto]" rows="2" class="textarea-paso margin-bottom-15" placeholder="Mira este vídeo/imagen antes de continuar..."></textarea>
            
            <div class="columnas-media-opciones">
                <div class="opcion-subida-media subida-imagen">
                    <label class="label-bold-color">📷 Opción A: Subir imagen</label>
                    <input type="file" name="imagen_paso_${contadorPasos}" accept="image/*" class="input-file-media">
                </div>
                <div class="opcion-subida-media subida-video">
                    <label class="label-bold-color">📺 Opción B: Pegar enlace de YouTube</label>
                    <input type="url" name="pasos[${contadorPasos}][video_url]" placeholder="https://www.youtube.com/watch?v=..." class="input-url-media">
                </div>
            </div>
        `;
    } 
    else if (tipo === 'juego') {
        let opcionesJuegos = '';
        
        if (typeof juegosDisponibles !== 'undefined' && Array.isArray(juegosDisponibles)) {
            juegosDisponibles.forEach(j => {
                opcionesJuegos += `<option value="${j.id_juego}">${j.titulo}</option>`;
            });
        }

        htmlInterno += `
            <label class="label-bold display-block-margin">Elige el juego para esta etapa:</label>
            <select name="pasos[${contadorPasos}][id_juego]" class="select-secuencia margin-bottom-15">
                ${opcionesJuegos}
            </select>
            
            <div class="caja-meta-juego">
                <label class="label-bold">Condición para avanzar:</label>
                <select name="pasos[${contadorPasos}][tipo_meta]" class="select-meta-condicion">
                    <option value="puntos">Conseguir estos puntos:</option>
                    <option value="segundos">Jugar estos segundos:</option>
                    <option value="partidas">Terminar este número de partidas:</option>
                </select>
                <input type="number" name="pasos[${contadorPasos}][meta_valor]" value="10" class="input-meta-valor">
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

window.onload = function() {
    agregarPaso('texto');
};