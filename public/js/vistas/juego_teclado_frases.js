document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const idJuegoReal = urlParams.get('id_juego') || 6; 
    const base_url_php = document.body.getAttribute('data-base-url') || '';

    const metaValor = parseInt(urlParams.get('meta')) || 0;
    const tipoMeta = urlParams.get('tipo') || '';

    const contenedorLetras = document.getElementById("contenedor-letras");
    const cajaPantalla = document.getElementById("caja-pantalla-frase");
    const ayudaTecla = document.getElementById("ayuda-tecla");
    const marcador = document.getElementById("marcador-frases");
    
    const btnTerminar = document.getElementById('btn-terminar-frases');
    const enlaceVolver = document.getElementById('enlace-volver-menu');
    const modal = document.getElementById('modal-confirmar-salida');
    const btnModalCancelar = document.getElementById('btn-modal-cancelar');
    const btnModalSalir = document.getElementById('btn-modal-salir-sin-guardar');

    const bancoFrases = [
        "HOLA MUNDO",
        "EL SOL BRILLA",
        "ME GUSTA EL CAFE",
        "EL ORDENADOR ES FACIL",
        "VIVA LA MUSICA",
        "HACE BUEN TIEMPO",
        "LA COMIDA ESTA LISTA",
        "ESTOY APRENDIENDO",
        "YA SE USAR EL TECLADO",
        "BUSCAR EN INTERNET",
        "PULSO LA BARRA GRANDE",
        "TE QUIERO MUCHO",
        "ABRAZOS PARA TODOS",
        "LLAMA POR TELEFONO",
        "UN DIA FELIZ",
        "ME GUSTA PASEAR",
        "EL CANTO DEL PAJARO",
        "FLORES EN EL BALCON",
        "UN BUEN LIBRO",
        "CASA Y JARDIN"
    ];

    let fraseActual = "";
    let indiceActual = 0;
    let frasesCompletadas = 0;
    let metaAlcanzada = false;

    function cargarNuevaFrase() {
        contenedorLetras.innerHTML = "";
        
        fraseActual = bancoFrases[Math.floor(Math.random() * bancoFrases.length)];
        indiceActual = 0;

        for (let i = 0; i < fraseActual.length; i++) {
            const span = document.createElement("span");
            span.classList.add("letra-frase");
            
            if (fraseActual[i] === " ") {
                span.innerHTML = "&nbsp;"; 
                span.style.borderBottomColor = "transparent"; 
                span.style.backgroundColor = "transparent";
            } else {
                span.innerText = fraseActual[i];
            }
            contenedorLetras.appendChild(span);
        }

        actualizarAyudaMecanografia();
    }

    function actualizarAyudaMecanografia() {
        const spans = contenedorLetras.getElementsByClassName("letra-frase");
        
        for (let s of spans) { s.classList.remove("letra-actual"); }

        if (indiceActual < fraseActual.length) {
            spans[indiceActual].classList.add("letra-actual");
            let siguienteChar = fraseActual[indiceActual];
            
            if (siguienteChar === " ") {
                ayudaTecla.innerText = "Siguiente tecla: [ BARRA ESPACIADORA ] (la tecla grande)";
            } else {
                ayudaTecla.innerText = "Siguiente tecla: [ " + siguienteChar + " ]";
            }
        }
    }

    window.addEventListener("keydown", function(evento) {
        // Bloquear escritura si el modal está abierto
        if (modal && modal.style.display === 'flex') return;

        let teclaPulsada = evento.key.toUpperCase();

        if (evento.key === " ") {
            evento.preventDefault();
            teclaPulsada = " ";
        }

        if (indiceActual >= fraseActual.length) return;

        let caracterEsperado = fraseActual[indiceActual];

        if (teclaPulsada === caracterEsperado) {
            const spans = contenedorLetras.getElementsByClassName("letra-frase");
            spans[indiceActual].classList.add("letra-acertada");
            indiceActual++;

            if (indiceActual < fraseActual.length) {
                actualizarAyudaMecanografia();
            } else {
                frasesCompletadas++;
                if (marcador) marcador.innerText = "Frases completadas: " + frasesCompletadas;
                
                if (metaValor > 0 && !metaAlcanzada) {
                    if (frasesCompletadas >= metaValor) {
                        metaAlcanzada = true;
                        if (ayudaTecla) ayudaTecla.innerText = "¡Fabuloso! Objetivo alcanzado.";
                        if (window.parent && window.parent !== window) {
                            window.parent.postMessage("juego_completado", "*");
                        }
                        return;
                    }
                }

                setTimeout(cargarNuevaFrase, 600);
            }
        } else {
            if (evento.key !== "Shift" && evento.key !== "Control" && evento.key !== "Alt") {
                cajaPantalla.classList.add("animacion-error");
                setTimeout(() => {
                    cajaPantalla.classList.remove("animacion-error");
                }, 400);
            }
        }
    });

    setInterval(() => {
        if (modal && modal.style.display !== 'flex') {
            window.focus();
        }
    }, 1000);

    cargarNuevaFrase();

    function procesarGuardadoFinal() {
        if (frasesCompletadas === 0) {
            window.location.href = base_url_php + 'practica';
            return;
        }

        if (btnTerminar) {
            btnTerminar.innerText = "Guardando partida...";
            btnTerminar.disabled = true;
        }

        const datosAEnviar = new URLSearchParams();
        datosAEnviar.append('id_juego', idJuegoReal);
        datosAEnviar.append('puntos', frasesCompletadas);

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

            if (frasesCompletadas === 0) {
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
        const titulo = document.getElementById('titulo-dobleclick-juego'); // Cambia el ID según el juego
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