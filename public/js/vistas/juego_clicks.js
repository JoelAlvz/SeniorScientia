document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const idJuegoReal = urlParams.get('id_juego') || 3; // Supongamos que 3 es el ID asignado a Clics
    const base_url_php = document.body.getAttribute('data-base-url') || '';

    const metaValor = parseInt(urlParams.get('meta')) || 0;
    const tipoMeta = urlParams.get('tipo') || '';

    let puntos = 0;
    let metaAlcanzada = false;
    let tipoClickActual = "izquierdo"; 

    const zonaJuego = document.getElementById("zona-juego-clicks");
    const objetivo = document.getElementById("objetivo-click");
    const marcador = document.getElementById("marcador-puntos");
    const textoRequerido = document.getElementById("tipo-click-requerido");
    
    const btnTerminar = document.getElementById('btn-terminar-practica');
    const enlaceVolver = document.getElementById('enlace-volver-menu');
    const modal = document.getElementById('modal-confirmar-salida');
    const btnModalCancelar = document.getElementById('btn-modal-cancelar');
    const btnModalSalir = document.getElementById('btn-modal-salir-sin-guardar');

    zonaJuego.addEventListener("contextmenu", function(e) {
        e.preventDefault();
    });

    function moverObjetivo() {
        const anchoTablero = zonaJuego.clientWidth - 100;
        const altoTablero = zonaJuego.clientHeight - 100;

        const posX = Math.floor(Math.random() * Math.max(anchoTablero, 50));
        const posY = Math.floor(Math.random() * Math.max(altoTablero, 50));

        objetivo.style.left = posX + "px";
        objetivo.style.top = posY + "px";

        if (Math.random() > 0.5) {
            tipoClickActual = "izquierdo";
            if(textoRequerido) {
                textoRequerido.innerText = "CLIC IZQUIERDO";
                textoRequerido.style.background = "#007bff";
            }
            objetivo.style.borderColor = "#007bff";
            objetivo.style.background = "#007bff";
            objetivo.innerText = "¡IZQ!";
        } else {
            tipoClickActual = "derecho";
            if(textoRequerido) {
                textoRequerido.innerText = "CLIC DERECHO";
                textoRequerido.style.background = "#dc3545";
            }
            objetivo.style.borderColor = "#dc3545";
            objetivo.style.background = "#dc3545";
            objetivo.innerText = "¡DER!";
        }
    }

    objetivo.addEventListener("mousedown", function(evento) {
        if (modal && modal.style.display === 'flex') return;

        evento.preventDefault();
        evento.stopPropagation();

        let clickCorrecto = false;

        if (evento.button === 0 && tipoClickActual === "izquierdo") {
            clickCorrecto = true;
        } else if (evento.button === 2 && tipoClickActual === "derecho") {
            clickCorrecto = true;
        }

        if (clickCorrecto) {
            puntos++;
            if (marcador) marcador.innerText = "Puntos: " + puntos;
            
            objetivo.style.transform = "scale(0.8)";

            if (metaValor > 0 && !metaAlcanzada) {
                if (puntos >= metaValor) {
                    metaAlcanzada = true;
                    if (window.parent && window.parent !== window) {
                        // Avisa directamente al contenedor padre que ya terminamos
                        window.parent.postMessage("juego_completado", "*");
                    }
                }
            }

            setTimeout(() => {
                objetivo.style.transform = "scale(1)";
                moverObjetivo();
            }, 120);

        } else {
            objetivo.style.background = "#64748b";
            objetivo.style.borderColor = "#475569";
            objetivo.innerText = "❌ ¡Oops!";
            setTimeout(() => {
                objetivo.style.background = (tipoClickActual === "izquierdo") ? "#007bff" : "#dc3545";
                objetivo.style.borderColor = (tipoClickActual === "izquierdo") ? "#007bff" : "#dc3545";
                objetivo.innerText = (tipoClickActual === "izquierdo") ? "¡IZQ!" : "¡DER!";
            }, 400);
        }
    });

    moverObjetivo();

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