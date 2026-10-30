document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const idJuegoReal = urlParams.get('id_juego') || 9; // ID correspondiente al juego de la rueda
    const base_url_php = document.body.getAttribute('data-base-url') || '';

    const metaValor = parseInt(urlParams.get('meta')) || 0;
    
    let puntos = 0;
    let lucesRestantes = 3;
    let metaAlcanzada = false;

    const totalPisos = 10; 
    const ventanasPorPiso = 3; 
    const edificio = document.getElementById('edificio');
    let ventanasValidasParaSobres = [];

    const contadorElemento = document.getElementById('contador-texto');
    const mensajeExito = document.getElementById('mensaje-exito');
    const btnTerminar = document.getElementById('btn-terminar-practica');
    const enlaceVolver = document.getElementById('enlace-volver-menu');
    const modal = document.getElementById('modal-confirmar-salida');
    const btnModalCancelar = document.getElementById('btn-modal-cancelar');
    const btnModalSalir = document.getElementById('btn-modal-salir-sin-guardar');

    // Función auxiliar para forzar rutas absolutas correctas
    function obtenerUrlAbsoluta(ruta) {
        let base = base_url_php.trim();
        if (!base) {
            base = window.location.origin + '/SeniorScientia/public/';
        }
        if (!base.endsWith('/')) base += '/';
        if (ruta.startsWith('/')) ruta = ruta.substring(1);
        return base + ruta;
    }

    // Comunicación con el marco principal (iframe) y cursor de accesibilidad
    const zonaJuego = document.body; 
    if (zonaJuego) {
        zonaJuego.addEventListener('mouseenter', () => {
            window.parent.postMessage('raton-dentro-del-juego', '*');
        });

        zonaJuego.addEventListener('mouseleave', () => {
            window.parent.postMessage('raton-salio-del-juego', '*');
        });
    }

    // 1. Construir el edificio dinámicamente una sola vez al cargar
    if (edificio) {
        for (let piso = 0; piso < totalPisos; piso++) {
            let topPos = 40 + (piso * 150); 
            
            for (let v = 0; v < ventanasPorPiso; v++) {
                let leftPos = 55 + (v * 120); 

                let ventana = document.createElement('div');
                ventana.className = 'ventana-edificio';
                ventana.style.top = topPos + 'px';
                ventana.style.left = leftPos + 'px';
                ventana.dataset.piso = piso;
                
                edificio.appendChild(ventana);

                // Excluimos las dos plantas superiores para obligar a hacer scroll
                if (piso >= 2) {
                    ventanasValidasParaSobres.push(ventana);
                }
            }
        }
    }

    // Función para activar una nueva ronda de luces aleatorias manteniendo los estilos y puntos acumulados
        function iniciarNuevaRonda() {
            lucesRestantes = 3;
            if (contadorElemento) contadorElemento.innerText = lucesRestantes;
            if (mensajeExito) mensajeExito.style.display = 'none';

            // Restablecer todas las ventanas del edificio a su estado original con estilos limpios
            ventanasValidasParaSobres.forEach(v => {
                v.classList.remove('tiene-sobre');
                v.innerHTML = '';
                delete v.dataset.encontrado;
                // Limpiamos los estilos en línea que se añadieron al apagar la luz para que recuperen su CSS original
                v.style.background = '';
                v.style.borderColor = '';
                v.style.transform = '';
            });

            let indicesAleatorios = [];
            while(indicesAleatorios.length < 3 && ventanasValidasParaSobres.length > 0) {
                let randomIndex = Math.floor(Math.random() * ventanasValidasParaSobres.length);
                if(!indicesAleatorios.includes(randomIndex)) {
                    indicesAleatorios.push(randomIndex);
                }
            }

            indicesAleatorios.forEach(index => {
                let ventanaSeleccionada = ventanasValidasParaSobres[index];
                if (ventanaSeleccionada) {
                    ventanaSeleccionada.classList.add('tiene-sobre');
                    ventanaSeleccionada.innerHTML = '💡';

                    // Reasignar el evento de clic para las nuevas luces
                    ventanaSeleccionada.onclick = function() {
                        if (modal && modal.style.display === 'flex') return;
                        if (!this.dataset.encontrado) {
                            this.dataset.encontrado = "true";
                            this.style.background = "#334155"; 
                            this.style.borderColor = "#64748b";
                            this.innerHTML = '✔️'; 
                            
                            lucesRestantes--;
                            puntos++; // Acumula puntos de forma continuada
                            
                            if (contadorElemento) contadorElemento.innerText = lucesRestantes;

                            // Verificar meta del tutor si aplica
                            if (metaValor > 0 && !metaAlcanzada) {
                                if (puntos >= metaValor) {
                                    metaAlcanzada = true;
                                    if (window.parent && window.parent !== window) {
                                        window.parent.postMessage("juego_completado", "*");
                                    }
                                }
                            }

                            // Si apaga las 3 luces de esta ronda, muestra aviso y carga otra ronda automáticamente
                            if (lucesRestantes === 0) {
                                if (mensajeExito) mensajeExito.style.display = 'block';

                                setTimeout(() => {
                                    iniciarNuevaRonda(); // Genera nuevas luces manteniendo los puntos acumulados
                                }, 1500);
                            }
                        }
                    };
                }
            });
        }

    // Iniciar la primera ronda
    iniciarNuevaRonda();

    // Función para procesar y enviar el total de puntos acumulados al servidor
    function procesarGuardadoFinal() {
        if (puntos === 0) {
            window.location.href = obtenerUrlAbsoluta('practica');
            return;
        }

        if (btnTerminar) {
            btnTerminar.innerText = "Guardando partida...";
            btnTerminar.disabled = true;
        }

        const datosAEnviar = new URLSearchParams();
        datosAEnviar.append('id_juego', idJuegoReal);
        datosAEnviar.append('puntos', puntos);

        fetch(obtenerUrlAbsoluta('practica/guardarPuntuacion'), {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: datosAEnviar.toString()
        })
        .then(res => res.json())
        .then(data => {
            if (data.nuevo_record === true) {
                window.location.href = obtenerUrlAbsoluta('practica?juego=' + idJuegoReal + '&record=1');
            } else {
                window.location.href = obtenerUrlAbsoluta('practica?juego=' + idJuegoReal);
            }
        })
        .catch(err => {
            console.error("Error en la petición de guardado:", err);
            window.location.href = obtenerUrlAbsoluta('practica');
        });
    }

    // Eventos de control de botones y modales
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
                window.location.href = obtenerUrlAbsoluta('dashboard');
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
            window.location.href = obtenerUrlAbsoluta('dashboard');
        });
    }
});