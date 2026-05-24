document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const idJuegoReal = urlParams.get('id_juego') || 4; 
    const base_url_php = document.body.getAttribute('data-base-url') || '';

    const metaValor = parseInt(urlParams.get('meta')) || 0;
    const tipoMeta = urlParams.get('tipo') || '';

    let puntos = 0;
    let metaAlcanzada = false;
    let bloqueado = false; 

    const tablero = document.getElementById("tablero-dobleclick");
    const cofre = document.getElementById("cofre-objetivo");
    const imgCofre = document.getElementById("img-cofre");
    const marcador = document.getElementById("marcador-cofres");

    const btnTerminar = document.getElementById('btn-terminar-practica');
    const enlaceVolver = document.getElementById('enlace-volver-menu');
    const modal = document.getElementById('modal-confirmar-salida');
    const btnModalCancelar = document.getElementById('btn-modal-cancelar');
    const btnModalSalir = document.getElementById('btn-modal-salir-sin-guardar');

    function moverCofre() {
        bloqueado = false;
        if (imgCofre) {
            imgCofre.src = base_url_php + "img/cofreCerrado.png";
        }

        const anchoMax = tablero.clientWidth - 130;
        const altoMax = tablero.clientHeight - 130;

        const posX = Math.floor(Math.random() * Math.max(anchoMax, 40));
        const posY = Math.floor(Math.random() * Math.max(altoMax, 40));

        if (cofre) {
            cofre.style.left = posX + "px";
            cofre.style.top = posY + "px";
        }
    }

    if (cofre) {
        cofre.addEventListener("dblclick", function(e) {
            if (modal && modal.style.display === 'flex') return;
            if (bloqueado) return;
            
            bloqueado = true;
            puntos++;
            
            if (marcador) marcador.innerText = "Puntos: " + puntos;
            if (imgCofre) imgCofre.src = base_url_php + "img/cofreAbierto.png";
            
            cofre.style.transform = "scale(1.2)";

            if (metaValor > 0 && !metaAlcanzada) {
                if (puntos >= metaValor) {
                    metaAlcanzada = true;
                    if (window.parent && window.parent !== window) {
                        window.parent.postMessage("juego_completado", "*");
                    }
                }
            }

            setTimeout(() => {
                cofre.style.transform = "scale(1)";
                moverCofre();
            }, 600); 
        });
    }

    moverCofre();

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
        // 1. Ocultar elementos innecesarios dentro del iframe si es necesario
        const titulo = document.getElementById('titulo-dobleclick-juego'); // Cambia el ID según el juego
        const instruccion = document.getElementById('texto-instruccion');

        if (titulo) titulo.style.display = 'none';
        
        // 2. Comunicar al padre cuando el ratón entra o sale del área del juego
        document.addEventListener('mousemove', function() {
            window.parent.postMessage('raton-dentro-del-juego', '*');
        });

        document.addEventListener('mouseleave', function() {
            window.parent.postMessage('raton-salio-del-juego', '*');
        });
    }
});