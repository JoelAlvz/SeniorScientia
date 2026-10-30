document.addEventListener('DOMContentLoaded', () => {
    const baseUrl = document.body.getAttribute('data-base-url') || '';
    const escenario = document.getElementById('escenario');
    const papelera = document.getElementById('cesta'); 
    const marcadorPuntos = document.getElementById('puntos');
    const ID_JUEGO = 8; 

    let puntos = 0;
    const contenedorJuego = document.querySelector('.juego-seleccion-container');

    const urlParams = new URLSearchParams(window.location.search);
    const idJuegoReal = urlParams.get('id_juego') || ID_JUEGO;
    const metaValor = parseInt(urlParams.get('meta')) || 0;
    let metaAlcanzada = false;

    const btnTerminar = document.getElementById('btn-terminar-practica');
    const enlaceVolver = document.getElementById('enlace-volver-menu');
    const modal = document.getElementById('modal-confirmar-salida');
    const btnModalCancelar = document.getElementById('btn-modal-cancelar');
    const btnModalSalir = document.getElementById('btn-modal-salir-sin-guardar');

    function verificarMeta() {
        if (metaValor > 0 && !metaAlcanzada) {
            if (puntos >= metaValor) {
                metaAlcanzada = true;
                if (window.parent && window.parent !== window) {
                    window.parent.postMessage("juego_completado", "*");
                }
            }
        }
    }

    function procesarGuardadoFinal() {
        if (puntos === 0) {
            window.location.href = baseUrl + 'practica';
            return;
        }

        if (btnTerminar) {
            btnTerminar.innerText = "Guardando partida...";
            btnTerminar.disabled = true;
        }

        const datosAEnviar = new URLSearchParams();
        datosAEnviar.append('id_juego', idJuegoReal);
        datosAEnviar.append('puntos', puntos);

        fetch(baseUrl + 'practica/guardarPuntuacion', {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: datosAEnviar.toString()
        })
        .then(res => res.json())
        .then(data => {
            if (data.nuevo_record === true) {
                window.location.href = baseUrl + 'practica?juego=' + idJuegoReal + '&record=1';
            } else {
                window.location.href = baseUrl + 'practica?juego=' + idJuegoReal;
            }
        })
        .catch(err => {
            console.error("Error en la petición de guardado:", err);
            window.location.href = baseUrl + 'practica';
        });
    }

    if (btnTerminar) {
        btnTerminar.addEventListener('click', function(e) {
            e.preventDefault(); e.stopPropagation();
            procesarGuardadoFinal();
        });
    }

    if (enlaceVolver) {
        enlaceVolver.addEventListener('click', function(event) {
            event.preventDefault(); event.stopPropagation();

            if (puntos === 0) {
                window.location.href = baseUrl + 'dashboard';
                return;
            }

            if (modal) modal.style.display = 'flex';
        });
    }

    if (btnModalCancelar && modal) {
        btnModalCancelar.addEventListener('click', function(e) {
            e.preventDefault(); e.stopPropagation();
            modal.style.display = 'none';
        });
    }

    if (btnModalSalir) {
        btnModalSalir.addEventListener('click', function(e) {
            e.preventDefault(); e.stopPropagation();
            window.location.href = baseUrl + 'dashboard';
        });
    }
    // ----------------------------------------------------------

    if (contenedorJuego) {
        // Cuando el ratón entra en el juego, avisa a la página principal para ocultar el cursor resaltado
        contenedorJuego.addEventListener('mouseenter', () => {
            window.parent.postMessage('raton-dentro-del-juego', '*');
        });

        // Cuando el ratón sale del juego, avisa para volver a mostrarlo
        contenedorJuego.addEventListener('mouseleave', () => {
            window.parent.postMessage('raton-salio-del-juego', '*');
        });
    }

    const objetosIconos = [
        { icono: '📄', nombre: 'Archivo.txt' },
        { icono: '📁', nombre: 'Carpeta' },
        { icono: '🖼️', nombre: 'Imagen.jpg' },
        { icono: '🎵', nombre: 'Canción.mp3' },
        { icono: '🎬', nombre: 'Película.avi' }
    ];

    let isSelecting = false;
    let selectionBox = null;
    let startX, startY;
    let seleccionados = new Set();
    
    let isDraggingGroup = false;
    let dragStartX, dragStartY;
    let initialPositions = new Map();

    function generarEscritorio() {
        const escenario = document.getElementById('escenario');
        selectionBox = document.getElementById('selection-box');

        // Limpiar solo los iconos
        escenario.querySelectorAll('.objeto-recolectable').forEach(el => el.remove());

        seleccionados.clear();
        initialPositions.clear();

        const anchoEscenario = escenario.offsetWidth > 50 ? escenario.offsetWidth : window.innerWidth;
        const altoEscenario = escenario.offsetHeight > 50 ? escenario.offsetHeight : window.innerHeight;

        const cantidadIconos = 6;
        const anchoIcono = 70;
        const altoIcono = 80;

        const margenSeguridadX = 20;
        const margenSeguridadY = 20;

        const anchoMaximoUtil = anchoEscenario - anchoIcono - (margenSeguridadX * 2);
        const altoMaximoUtil = altoEscenario - altoIcono - (margenSeguridadY * 2);

        const zonaPapelera = {
            left: 10,
            top: 10,
            width: 100,
            height: 110
        };

        const iconosGenerados = [];

        for (let i = 0; i < cantidadIconos; i++) {
            const data = objetosIconos[Math.floor(Math.random() * objetosIconos.length)];
            const item = document.createElement('div');
            item.className = 'objeto-recolectable';

            let posX, posY;
            let intentos = 0;
            let solapa;

            do {
                solapa = false;
                posX = Math.random() * Math.max(50, anchoMaximoUtil) + margenSeguridadX;
                posY = Math.random() * Math.max(50, altoMaximoUtil) + margenSeguridadY;

                const chocaConPapelera = (
                    posX < zonaPapelera.left + zonaPapelera.width &&
                    posX + anchoIcono > zonaPapelera.left &&
                    posY < zonaPapelera.top + zonaPapelera.height &&
                    posY + altoIcono > zonaPapelera.top
                );

                if (chocaConPapelera) {
                    solapa = true;
                    intentos++;
                    continue;
                }

                for (let existente of iconosGenerados) {
                    if (
                        posX < existente.x + anchoIcono + 8 &&
                        posX + anchoIcono + 8 > existente.x &&
                        posY < existente.y + altoIcono + 8 &&
                        posY + altoIcono + 8 > existente.y
                    ) {
                        solapa = true;
                        break;
                    }
                }
                intentos++;
            } while (solapa && intentos < 100);

            iconosGenerados.push({ x: posX, y: posY });

            item.style.left = `${posX}px`;
            item.style.top = `${posY}px`;

            item.innerHTML = `
                <div class="icon-visual">${data.icono}</div>
                <div class="icon-label">${data.nombre}</div>
            `;

            item.addEventListener('mousedown', (e) => {
                e.stopPropagation();
                iniciarArrastreGrupo(e, item);
            });

            escenario.appendChild(item);
        }
    }

    window.addEventListener('resize', () => {
        if (document.querySelectorAll('.objeto-recolectable').length > 0) {
            generarEscritorio();
        }
    });

    escenario.addEventListener('mousedown', (e) => {
        isSelecting = true;
        const rect = escenario.getBoundingClientRect();
        startX = e.clientX - rect.left;
        startY = e.clientY - rect.top;

        if (selectionBox) {
            selectionBox.style.left = `${startX}px`;
            selectionBox.style.top = `${startY}px`;
            selectionBox.style.width = '0px';
            selectionBox.style.height = '0px';
            selectionBox.style.display = 'block';
        }

        escenario.querySelectorAll('.objeto-recolectable').forEach(icon => {
            icon.classList.remove('seleccionado');
        });
        seleccionados.clear();
    });

    window.addEventListener('mousemove', (e) => {
        const rect = escenario.getBoundingClientRect();

        if (isSelecting && selectionBox) {
            const currentX = e.clientX - rect.left;
            const currentY = e.clientY - rect.top;

            const width = Math.abs(currentX - startX);
            const height = Math.abs(currentY - startY);
            const left = (currentX < startX) ? currentX : startX;
            const top = (currentY < startY) ? currentY : startY;

            selectionBox.style.width = `${width}px`;
            selectionBox.style.height = `${height}px`;
            selectionBox.style.left = `${left}px`;
            selectionBox.style.top = `${top}px`;

            const boxRect = selectionBox.getBoundingClientRect();
            
            escenario.querySelectorAll('.objeto-recolectable').forEach(icon => {
                const iconRect = icon.getBoundingClientRect();

                const isInside = !(iconRect.right < boxRect.left || 
                                   iconRect.left > boxRect.right || 
                                   iconRect.bottom < boxRect.top || 
                                   iconRect.top > boxRect.bottom);

                if (isInside) {
                    icon.classList.add('seleccionado');
                    seleccionados.add(icon);
                } else {
                    icon.classList.remove('seleccionado');
                    seleccionados.delete(icon);
                }
            });
        }

        if (isDraggingGroup) {
            const dx = e.clientX - dragStartX;
            const dy = e.clientY - dragStartY;

            seleccionados.forEach(icon => {
                const posInicial = initialPositions.get(icon);
                if (posInicial) {
                    let newX = posInicial.x + dx;
                    let newY = posInicial.y + dy;

                    const anchoIcono = 70;
                    const altoIcono = 80;
                    
                    newX = Math.max(0, Math.min(newX, escenario.offsetWidth - anchoIcono));
                    newY = Math.max(0, Math.min(newY, escenario.offsetHeight - altoIcono));

                    icon.style.left = `${newX}px`;
                    icon.style.top = `${newY}px`;
                }
            });

            if (papelera) {
                const papeleraRect = papelera.getBoundingClientRect();
                if (e.clientX > papeleraRect.left && e.clientX < papeleraRect.right &&
                    e.clientY > papeleraRect.top && e.clientY < papeleraRect.bottom) {
                    papelera.classList.add('drag-over');
                } else {
                    papelera.classList.remove('drag-over');
                }
            }
        }
    });

    window.addEventListener('mouseup', (e) => {
        if (isSelecting) {
            isSelecting = false;
            if (selectionBox) selectionBox.style.display = 'none';
        }
        
        if (isDraggingGroup) {
            isDraggingGroup = false;
            finalizarArrastreGrupo(e);
        }
    });

    function iniciarArrastreGrupo(e, itemArrastrado) {
        if (!itemArrastrado.classList.contains('seleccionado')) {
            escenario.querySelectorAll('.objeto-recolectable').forEach(icon => icon.classList.remove('seleccionado'));
            seleccionados.clear();
            itemArrastrado.classList.add('seleccionado');
            seleccionados.add(itemArrastrado);
        }

        if (seleccionados.size === 0) return;

        isDraggingGroup = true;
        dragStartX = e.clientX;
        dragStartY = e.clientY;

        initialPositions.clear();
        seleccionados.forEach(icon => {
            initialPositions.set(icon, {
                x: parseInt(icon.style.left) || 0,
                y: parseInt(icon.style.top) || 0
            });
            icon.style.cursor = 'grabbing';
            icon.style.zIndex = '5';
        });
    }

    function finalizarArrastreGrupo(e) {
        if (!papelera) return;
        const papeleraRect = papelera.getBoundingClientRect();
        papelera.classList.remove('drag-over');
        
        const mouseX = e.clientX;
        const mouseY = e.clientY;

        const depositados = (mouseX > papeleraRect.left && mouseX < papeleraRect.right &&
                           mouseY > papeleraRect.top && mouseY < papeleraRect.bottom);

        seleccionados.forEach(icon => {
            icon.style.cursor = 'grab';
            icon.style.zIndex = '1';

            if (depositados) {
                icon.style.transform = 'scale(0)';
                icon.style.transition = 'transform 0.3s';
                setTimeout(() => icon.remove(), 300);
            }
        });

        if (depositados && seleccionados.size > 0) {
            puntos += seleccionados.size;
            marcadorPuntos.innerText = `Puntos: ${puntos}`;
            verificarMeta(); // <-- Comprueba si cumple la meta del tutor
            seleccionados.clear();
            
            setTimeout(() => {
                if (escenario.querySelectorAll('.objeto-recolectable').length === 0) {
                    generarEscritorio();
                }
            }, 350);
        }
    }

    generarEscritorio();
});