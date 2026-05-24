document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const idJuegoReal = urlParams.get('id_juego') || 5; // Asignamos ID único para diferenciarlo de los clics
    const base_url_php = document.body.getAttribute('data-base-url') || '';

    const metaValor = parseInt(urlParams.get('meta')) || 0;
    const tipoMeta = urlParams.get('tipo') || '';

    let puntos = 0;
    let metaAlcanzada = false;

    const tablero = document.getElementById("tablero-arrastrar");
    const manzana = document.getElementById("manzana-arrastrable");
    const cesta = document.getElementById("cesta-destino");
    const imgCesta = document.getElementById("img-cesta"); 
    const textoCesta = document.getElementById("texto-cesta-estado");
    const marcador = document.getElementById("marcador-manzanas");

    const btnTerminar = document.getElementById('btn-terminar-practica');
    const enlaceVolver = document.getElementById('enlace-volver-menu');
    const modal = document.getElementById('modal-confirmar-salida');
    const btnModalCancelar = document.getElementById('btn-modal-cancelar');
    const btnModalSalir = document.getElementById('btn-modal-salir-sin-guardar');

    function reubicarManzana() {
        if (manzana) {
            manzana.style.transform = "scale(1)";
            
            const altoMaximo = tablero.clientHeight - 110;
            const posX = Math.floor(Math.random() * 80) + 30; 
            const posY = Math.floor(Math.random() * Math.max(altoMaximo, 40));

            manzana.style.left = posX + "px";
            manzana.style.top = posY + "px";
        }

        if (imgCesta) imgCesta.src = base_url_php + "img/cestaCerrada.png";
        if (textoCesta) textoCesta.innerText = "¡TRAE AQUÍ! 🧺";
    }

    // Mecánica Drag & Drop (Arrastrar)
    if (manzana) {
        manzana.addEventListener("dragstart", function(e) {
            // Cancelar arrastre si el modal de salida está activo
            if (modal && modal.style.display === 'flex') {
                e.preventDefault();
                return;
            }
            manzana.style.cursor = "grabbing";
            e.dataTransfer.setData("text/plain", e.target.id);
        });
    }

    if (cesta) {
        cesta.addEventListener("dragenter", function(e) {
            e.preventDefault();
            if (imgCesta) imgCesta.src = base_url_php + "img/cestaAbierta.png"; 
            if (textoCesta) textoCesta.innerText = "¡SUÉLTALA! 😮";
            cesta.style.background = "rgba(34, 197, 94, 0.3)";
        });

        cesta.addEventListener("dragover", function(e) {
            e.preventDefault();
        });

        cesta.addEventListener("dragleave", function() {
            if (imgCesta) imgCesta.src = base_url_php + "img/cestaCerrada.png";
            if (textoCesta) textoCesta.innerText = "¡TRAE AQUÍ! 🧺";
            cesta.style.background = "rgba(34, 197, 94, 0.1)";
        });

        cesta.addEventListener("drop", function(e) {
            e.preventDefault();
            
            if (imgCesta) imgCesta.src = base_url_php + "img/cestaCerrada.png";
            if (textoCesta) textoCesta.innerText = "¡CAPTURADA! 🎉";
            cesta.style.background = "rgba(34, 197, 94, 0.1)";

            puntos++;
            if (marcador) marcador.innerText = "Puntos: " + puntos;

            if (manzana) manzana.style.transform = "scale(0)";

            if (metaValor > 0 && !metaAlcanzada) {
                if (puntos >= metaValor) {
                    metaAlcanzada = true;
                    if (window.parent && window.parent !== window) {
                        window.parent.postMessage("juego_completado", "*");
                    }
                }
            }

            setTimeout(() => {
                reubicarManzana();
            }, 400); 
        });
    }

    if (tablero) {
        tablero.addEventListener("dragover", function(e) {
            e.preventDefault();
        });

        tablero.addEventListener("drop", function(e) {
            e.preventDefault();
            const rect = tablero.getBoundingClientRect();
            const x = e.clientX - rect.left - 45; 
            const y = e.clientY - rect.top - 45;

            if (e.target.id === "tablero-arrastrar" && manzana) {
                manzana.style.left = Math.max(0, Math.min(x, tablero.clientWidth - 95)) + "px";
                manzana.style.top = Math.max(0, Math.min(y, tablero.clientHeight - 95)) + "px";
                
                if (imgCesta) imgCesta.src = base_url_php + "img/cestaCerrada.png";
                if (textoCesta) textoCesta.innerText = "¡TRAE AQUÍ! 🧺";
            }
        });
    }

    reubicarManzana();

    function procesarGuardadoFinal() {
        if (puntos === 0) {
            window.location.href = base_url_php + 'practica';
            return;
        }

        if (btnTerminar) {
            btnTerminar.innerText = "Guardando partida...";
            btnTerminar.disabled = true;
        }

        const datosAEnviar = new URLSearchParams();
        datosAEnviar.append('id_juego', idJuegoReal);
        datosAEnviar.append('puntos', puntos);

        fetch(base_url_php + 'practica/guardarPuntuacion', {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: datosAEnviar.toString()
        })
        .then(res => res.json())
        .then(data => {
            if (data.nuevo_record === true) {
                window.location.href = base_url_php + 'practica?juego=' + idJuegoReal + '&record=1';
            } else {
                window.location.href = base_url_php + 'practica?juego=' + idJuegoReal;
            }
        })
        .catch(err => {
            console.error("Error en la petición de guardado:", err);
            window.location.href = base_url_php + 'practica';
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
                window.location.href = base_url_php + 'dashboard';
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
            window.location.href = base_url_php + 'dashboard';
        });
    }
    if (window.self !== window.top) {
        const titulo = document.getElementById('titulo-dobleclick-juego'); 
        const instruccion = document.getElementById('texto-instruccion');

        if (titulo) titulo.style.display = 'none';
        
        document.addEventListener('mousemove', function() {
            window.parent.postMessage('raton-dentro-del-juego', '*');
        });

        document.addEventListener('mouseleave', function() {
            window.parent.postMessage('raton-salio-del-juego', '*');
        });
    }
});