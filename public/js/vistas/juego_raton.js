document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const metaPuntos = parseInt(urlParams.get('meta')) || 0;
    const idActividad = urlParams.get('actividad');
    const idJuegoReal = urlParams.get('id_juego') || 1; 
    
    const base_url_php = document.body.getAttribute('data-base-url') || '';

    let puntos = 0;
    const objetivo = document.getElementById('objetivo');
    const marcador = document.getElementById('puntos');
    const zona = document.getElementById('zona-juego');
    
    const btnTerminar = document.getElementById('btn-terminar-practica');
    const enlaceVolver = document.getElementById('enlace-volver-menu');
    const modal = document.getElementById('modal-confirmar-salida');
    const btnModalCancelar = document.getElementById('btn-modal-cancelar');
    const btnModalSalir = document.getElementById('btn-modal-salir-sin-guardar');

    if (window.self !== window.top) {
        const titulo = document.getElementById('titulo-raton-juego');
        const mensajeMeta = document.getElementById('mensaje-meta-tutor');
        const instruccion = document.getElementById('texto-instruccion');

        if (titulo) titulo.style.display = 'none';
        if (mensajeMeta) mensajeMeta.style.display = 'none';
        if (enlaceVolver) enlaceVolver.style.display = 'none';
        if (btnTerminar) btnTerminar.style.display = 'none';
        
        if (instruccion) {
            instruccion.style.color = '#ffffff';
        }
    }

    if (objetivo) {
        objetivo.addEventListener('mouseover', function() {
            moverObjetivo();
        });
    }
    // ------------------------------------

    function moverObjetivo() {
        if (!zona || !objetivo) return;
        
        puntos++;
        if (marcador) {
            marcador.innerText = "Puntos: " + puntos;
        }

        objetivo.style.transform = "none";

        const maxX = zona.clientWidth - objetivo.clientWidth;
        const maxY = zona.clientHeight - objetivo.clientHeight;

        const posX = Math.floor(Math.random() * maxX);
        const posY = Math.floor(Math.random() * maxY);

        objetivo.style.left = posX + 'px';
        objetivo.style.top = posY + 'px';

        objetivo.style.backgroundColor = "orange";
        setTimeout(() => { objetivo.style.backgroundColor = "#007bff"; }, 200);

        if (metaPuntos > 0 && puntos >= metaPuntos) {
            finalizarPasoDeActividad();
        }
    }

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

            if (modal) {
                modal.style.display = 'flex';
            }
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

    function finalizarPasoDeActividad() {
        if (window.parent) {
            window.parent.postMessage("juego_completado", "*");
        }
        if (document.getElementById('btn-continuar')) return;

        const urlActual = window.location.search || window.parent.location.search;
        const params = new URLSearchParams(urlActual);
        const pasoActual = parseInt(params.get('paso')) || 1;
        const siguientePaso = pasoActual + 1;

        const btn = document.createElement('a');
        btn.id = 'btn-continuar';
        btn.innerText = "¡META LOGRADA! PULSA AQUÍ PARA CONTINUAR";
        
        btn.href = base_url_php + "practica/verSecuencia/" + idActividad + "?paso=" + siguientePaso;
        
        btn.style = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 40px;
            background: #28a745;
            color: white;
            font-size: 2rem;
            text-align: center;
            border-radius: 20px;
            text-decoration: none;
            box-shadow: 0 0 50px rgba(0,0,0,0.5);
            font-weight: bold;
            z-index: 1000;
            border: 5px solid white;
        `;
        
        document.body.appendChild(btn);
    }
});