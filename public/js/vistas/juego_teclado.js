document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    // Cambiamos el ID por defecto al 2 para la tabla ranking_juegos
    const idJuegoReal = urlParams.get('id_juego') || 2; 
    const base_url_php = document.body.getAttribute('data-base-url') || '';

    let puntos = 0;
    let letraActual = 'A';
    
    const letrasPosibles = "ABCDEFGHIJKMNPRSTUVWXYZ";

    const cuadroLetra = document.getElementById('cuadro-letra');
    const marcador = document.getElementById('puntos');
    
    const btnTerminar = document.getElementById('btn-terminar-practica');
    const enlaceVolver = document.getElementById('enlace-volver-menu');
    const modal = document.getElementById('modal-confirmar-salida');
    const btnModalCancelar = document.getElementById('btn-modal-cancelar');
    const btnModalSalir = document.getElementById('btn-modal-salir-sin-guardar');

    function cambiarLetra() {
        let nuevaLetra;
        do {
            nuevaLetra = letrasPosibles[Math.floor(Math.random() * letrasPosibles.length)];
        } while (nuevaLetra === letraActual);

        letraActual = nuevaLetra;
        if (cuadroLetra) {
            cuadroLetra.innerText = letraActual;
        }
    }


    window.addEventListener('keydown', function(event) {
        if (modal && modal.style.display === 'flex') return;

        const teclaPulsada = event.key.toUpperCase();

        if (teclaPulsada === letraActual) {
            puntos++;
            if (marcador) marcador.innerText = "Puntos: " + puntos;

            if (cuadroLetra) {
                cuadroLetra.style.backgroundColor = "#28a745";
                cuadroLetra.style.color = "white";
                cuadroLetra.style.borderColor = "#28a745";
                cuadroLetra.style.transform = "scale(1.1)";
            }

            setTimeout(() => {
                if (cuadroLetra) {
                    cuadroLetra.style.backgroundColor = "white";
                    cuadroLetra.style.color = "#1e293b";
                    cuadroLetra.style.borderColor = "#007bff";
                    cuadroLetra.style.transform = "none";
                }
                cambiarLetra();
            }, 180);
        }
    });

    cambiarLetra();

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
            e.preventDefault();
            e.stopPropagation();
            procesarGuardadoFinal();
        });
    }


    if (enlaceVolver) {
        enlaceVolver.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();

            if (puntos === 0) {
                window.location.href = base_url_php + 'dashboard';
                return;
            }

            if (modal) modal.style.display = 'flex';
        });
    }

    if (btnModalCancelar && modal) {
        btnModalCancelar.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            modal.style.display = 'none';
        });
    }

    if (btnModalSalir) {
        btnModalSalir.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            window.location.href = base_url_php + 'dashboard';
        });
    }
});