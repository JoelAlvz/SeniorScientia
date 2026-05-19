
document.addEventListener("DOMContentLoaded", () => {
    const btnAcc = document.getElementById('btn-accesibilidad');
    const menuAcc = document.getElementById('menu-accesibilidad');

    if (btnAcc && menuAcc) {
        btnAcc.addEventListener('click', (e) => {
            e.stopPropagation(); 
            menuAcc.style.display = (menuAcc.style.display === 'none' || menuAcc.style.display === '') ? 'block' : 'none';
        });

        // Cerrar el menú si el usuario hace clic fuera de él
        document.addEventListener('click', (e) => {
            if (!menuAcc.contains(e.target) && e.target !== btnAcc) {
                menuAcc.style.display = 'none';
            }
        });
    }

    // Recuperar y aplicar la configuración guardada del alumno al cargar la página
    const textoGuardado = localStorage.getItem('accesibilidad-texto');
    if (textoGuardado) {
        aplicarNivelTexto(textoGuardado);
    }
    
    const contrasteGuardado = localStorage.getItem('accesibilidad-contraste');
    if (contrasteGuardado === 'activo') {
        document.body.classList.add('alto-contraste');
    }

    // ==================================================================
    // MOTOR DEL CURSOR ESPECIAL RESALTADO
    // ==================================================================
    
    // Crear e inyectar el círculo dinámicamente al cargar la página si no existe
    let circle = document.createElement('div');
    circle.id = 'custom-cursor-circle';
    circle.className = 'custom-cursor-circle';

    let centerDot = document.createElement('div');
    centerDot.className = 'custom-cursor-dot';
    circle.appendChild(centerDot);
    
    document.body.appendChild(circle);

    // Escuchar el movimiento del ratón para seguir las coordenadas con aceleración gráfica
    document.addEventListener('mousemove', (e) => {

        const x = e.clientX - 20;
        const y = e.clientY - 20;
        
        // Aplicamos la transformación 3D para máxima fluidez
        circle.style.transform = `translate3d(${x}px, ${y}px, 0)`;

        // Comprobamos si el ratón está pasando por encima de un elemento clickeable
        const target = e.target;
        if (target.tagName === 'A' || target.tagName === 'BUTTON' || target.closest('a') || target.closest('button') || target.type === 'submit') {
            circle.classList.add('on-link');
        } else {
            circle.classList.remove('on-link');
        }
    });
    // Efecto de "punto/explosión" al hacer click
    document.addEventListener('click', (e) => {
        // Solo si la función está activada
        if (document.body.classList.contains('cursor-especial-activo')) {
            let clickDot = document.createElement('div');
            clickDot.className = 'cursor-click-effect';
            clickDot.style.left = e.clientX + 'px';
            clickDot.style.top = e.clientY + 'px';
            document.body.appendChild(clickDot);

            // Eliminar el elemento una vez termine la animación CSS (400ms)
            setTimeout(() => {
                clickDot.remove();
            }, 400);
        }
    });

    // Cargar estado inicial del cursor desde LocalStorage
    const cursorGuardado = localStorage.getItem('accesibilidad-cursor');
    if (cursorGuardado === 'activo') {
        document.body.classList.add('cursor-especial-activo');
        circle.style.display = 'block';
        actualizarBotonCursor(true);
    }
});

// ==================================================================
// FUNCIONES GLOBALES (ACCESIBLES DESDE EL ONCLICK DEL HTML)
// ==================================================================

function cambiarTexto(nivel) {
    aplicarNivelTexto(nivel);
    localStorage.setItem('accesibilidad-texto', nivel);
}

function aplicarNivelTexto(nivel) {
    document.body.classList.remove('texto-grande', 'texto-gigante');
    if (nivel === 'grande') {
        document.body.classList.add('texto-grande');
    } else if (nivel === 'gigante') {
        document.body.classList.add('texto-gigante');
    }
}

function alternarContraste() {
    document.body.classList.toggle('alto-contraste');
    if (document.body.classList.contains('alto-contraste')) {
        localStorage.setItem('accesibilidad-contraste', 'activo');
    } else {
        localStorage.setItem('accesibilidad-contraste', 'inactivo');
    }
}

function alternarCursorEspecial() {
    const circle = document.getElementById('custom-cursor-circle');
    const activo = document.body.classList.toggle('cursor-especial-activo');
    
    if (activo) {
        if (circle) circle.style.display = 'block';
        localStorage.setItem('accesibilidad-cursor', 'activo');
        actualizarBotonCursor(true);
    } else {
        if (circle) circle.style.display = 'none';
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