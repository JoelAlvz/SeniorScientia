document.addEventListener("DOMContentLoaded", () => {
    const btnAcc = document.getElementById('btn-accesibilidad');
    const menuAcc = document.getElementById('menu-accesibilidad');

    if (btnAcc && menuAcc) {
        btnAcc.addEventListener('click', (e) => {
            e.stopPropagation(); 
            menuAcc.style.display = (menuAcc.style.display === 'none' || menuAcc.style.display === '') ? 'block' : 'none';
        });

        document.addEventListener('click', (e) => {
            if (!menuAcc.contains(e.target) && e.target !== btnAcc) {
                menuAcc.style.display = 'none';
            }
        });
    }

    //Recuperar y aplicar configuraciones desde LocalStorage
    const textoGuardado = localStorage.getItem('accesibilidad-texto');
    if (textoGuardado) aplicarNivelTexto(textoGuardado);
    
    const contrasteGuardado = localStorage.getItem('accesibilidad-contrast');
    if (contrasteGuardado === 'activo') document.body.classList.add('alto-contrast');

    // ==================================================================
    // MOTOR DEL CURSOR ESPECIAL RESALTADO (CORREGIDO)
    // ==================================================================
    let circle = document.createElement('div');
    circle.id = 'custom-cursor-circle';
    circle.className = 'custom-cursor-circle';
    
    circle.style.position = 'fixed';
    circle.style.left = '-100px';
    circle.style.top = '-100px';
    circle.style.visibility = 'hidden'; 
    circle.style.opacity = '0'; 

    let centerDot = document.createElement('div');
    centerDot.className = 'custom-cursor-dot';
    circle.appendChild(centerDot);
    document.body.appendChild(circle);

    document.addEventListener('mousemove', (e) => {
        const x = e.clientX - 20;
        const y = e.clientY - 20;
        
        circle.style.left = x + 'px';
        circle.style.top = y + 'px';

        if (document.body.classList.contains('cursor-especial-activo')) {
            circle.style.visibility = 'visible';
            circle.style.opacity = '1';
        } else {
            circle.style.visibility = 'hidden';
            circle.style.opacity = '0';
        }

        const target = e.target;
        if (target.tagName === 'A' || target.tagName === 'BUTTON' || target.closest('a') || target.closest('button') || target.type === 'submit') {
            circle.classList.add('on-link');
        } else {
            circle.classList.remove('on-link');
        }
    });

    document.addEventListener('click', (e) => {
        if (document.body.classList.contains('cursor-especial-activo')) {
            let clickDot = document.createElement('div');
            clickDot.className = 'cursor-click-effect';
            clickDot.style.left = e.clientX + 'px';
            clickDot.style.top = e.clientY + 'px';
            document.body.appendChild(clickDot);

            setTimeout(() => { clickDot.remove(); }, 400);
        }
    });

    const cursorGuardado = localStorage.getItem('accesibilidad-cursor');
    if (cursorGuardado === 'activo') {
        document.body.classList.add('cursor-especial-activo');
        actualizarBotonCursor(true);
    }

    // Cargar estado guardado persistente del asistente de voz
    const vozGuardada = localStorage.getItem('accesibilidad-voz');
    if (vozGuardada === 'activo') {
        document.body.classList.add('voz-asistida-activa');
        actualizarBotonVoz(true);
        activarEscuchadoresVoz();
    }
});

// ==================================================================
// CONFIGURACIONES INTERACTIVAS GLOBALES
// ==================================================================

function cambiarTexto(nivel) {
    aplicarNivelTexto(nivel);
    localStorage.setItem('accesibilidad-texto', nivel);
}

function aplicarNivelTexto(nivel) {
    document.body.classList.remove('texto-grande', 'texto-gigante');
    if (nivel === 'grande') document.body.classList.add('texto-grande');
    if (nivel === 'gigante') document.body.classList.add('texto-gigante');
}

function alternarContraste() {
    document.body.classList.toggle('alto-contraste');
    localStorage.setItem('accesibilidad-contraste', document.body.classList.contains('alto-contraste') ? 'activo' : 'inactivo');
}

function alternarCursorEspecial() {
    const circle = document.getElementById('custom-cursor-circle');
    const activo = document.body.classList.toggle('cursor-especial-activo');
    
    if (activo) {
        localStorage.setItem('accesibilidad-cursor', 'activo');
        actualizarBotonCursor(true);
    } else {
        if (circle) {
            circle.style.visibility = 'hidden';
            circle.style.opacity = '0';
        }
        localStorage.setItem('accesibilidad-cursor', 'inactivo');
        actualizarBotonCursor(false);
    }
}

function actualizarBotonCursor(isActivo) {
    const btn = document.getElementById('btn-cursor');
    if (btn) {
        if (isActivo) {
            btn.style.background = '#00e676';
            btn.style.color = '#000';
            btn.innerHTML = '✅ Cursor Resaltado';
        } else {
            btn.style.background = '#ff5722';
            btn.style.color = '#fff';
            btn.innerHTML = '🎯 Resaltar Ratón';
        }
    }
}

// ==================================================================
// ASISTENTE REPRODUCTOR DE VOZ (TEXT-TO-SPEECH)
// ==================================================================

function alternarVozAsistida() {
    const activo = document.body.classList.toggle('voz-asistida-activa');
    
    if (activo) {
        localStorage.setItem('accesibilidad-voz', 'activo');
        actualizarBotonVoz(true);
        activarEscuchadoresVoz();
        hablarTexto("Asistencia por voz activada");
    } else {
        localStorage.setItem('accesibilidad-voz', 'inactivo');
        actualizarBotonVoz(false);
        window.speechSynthesis.cancel();
    }
}

function actualizarBotonVoz(isActivo) {
    const btn = document.getElementById('btn-voz');
    if (btn) {
        if (isActivo) {
            btn.style.background = '#28a745';
            btn.style.color = '#fff';
            btn.innerHTML = '✅ Voz Activa';
        } else {
            btn.style.background = '#6f42c1';
            btn.style.color = '#fff';
            btn.innerHTML = '🔊 Activar Voz';
        }
    }
}

function hablarTexto(texto) {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        const textoLimpio = texto.replace(/[👋🎉🎯🌓👁️🥇✉️🔊✅]/g, '').trim();
        if (textoLimpio.length === 0) return;

        const utterance = new SpeechSynthesisUtterance(textoLimpio);
        utterance.lang = 'es-ES';
        utterance.rate = 0.85; 
        window.speechSynthesis.speak(utterance);
    }
}

function activarEscuchadoresVoz() {
    const elementosLegibles = document.querySelectorAll('h1, h2, h3, h4, a, button, .card p, strong, label');

    elementosLegibles.forEach(elemento => {
        if (!elemento.dataset.vozAsignada) {
            elemento.dataset.vozAsignada = "true";

            elemento.addEventListener('mouseover', (e) => {
                if (document.body.classList.contains('voz-asistida-activa')) {
                    e.stopPropagation(); 
                    let textoALeer = elemento.innerText || elemento.textContent;
                    
                    if (elemento.tagName === 'A' || elemento.tagName === 'BUTTON' || elemento.classList.contains('btn-grande')) {
                        textoALeer = "Botón: " + textoALeer;
                    }
                    hablarTexto(textoALeer);
                }
            });

            elemento.addEventListener('mouseleave', () => {
                if (document.body.classList.contains('voz-asistida-activa')) {
                    window.speechSynthesis.cancel();
                }
            });
        }
    });
}