function abrirModalTareas() {
    const modal = document.getElementById('modal-tareas-anteriores');
    if (modal) {
        modal.style.display = 'flex';
    }
}

function cerrarModalTareas() {
    const modal = document.getElementById('modal-tareas-anteriores');
    if (modal) {
        modal.style.display = 'none';
    }
}

window.addEventListener('click', function(event) {
    const modal = document.getElementById('modal-tareas-anteriores');
    if (event.target === modal) {
        cerrarModalTareas();
    }
});


let alumnosFiltrados = typeof arrayAlumnosInicial !== 'undefined' ? [...arrayAlumnosInicial] : [];
let paginaActual = 1;
const limitePorPagina = 8;

document.addEventListener("DOMContentLoaded", function() {
    if (document.getElementById('lista-dinamica-alumnos')) {
        filtrarYRenderizarAlumnos();
    }
});

function filtrarYRenderizarAlumnos() {
    const terminoBusqueda = document.getElementById('buscar-alumno-input')?.value.toLowerCase().trim() || "";
    
    if (terminoBusqueda === "") {
        alumnosFiltrados = [...arrayAlumnosInicial];
    } else {
        alumnosFiltrados = arrayAlumnosInicial.filter(al => 
            al.nombre.toLowerCase().includes(terminoBusqueda)
        );
    }
    
    paginaActual = 1;
    renderizarEstructuraLista();
}

function cambiarOrdenAlumnos(tipoOrden) {
    if (tipoOrden === 'AZ') {
        alumnosFiltrados.sort((a, b) => a.nombre.localeCompare(b.nombre));
    } else if (tipoOrden === 'ZA') {
        alumnosFiltrados.sort((a, b) => b.nombre.localeCompare(a.nombre));
    }
    renderizarEstructuraLista();
}

function renderizarEstructuraLista() {
    const contenedorLista = document.getElementById('lista-dinamica-alumnos');
    const contenedorPaginacion = document.getElementById('paginacion-alumnos-nav');
    
    if (!contenedorLista) return;
    
    contenedorLista.innerHTML = "";
    
    if (alumnosFiltrados.length === 0) {
        contenedorLista.innerHTML = `<p class="texto-vacio-alumnos">No se encontraron alumnos que coincidan con la búsqueda.</p>`;
        if (contenedorPaginacion) contenedorPaginacion.innerHTML = "";
        return;
    }

    const indiceInicial = (paginaActual - 1) * limitePorPagina;
    const indiceFinal = indiceInicial + limitePorPagina;
    const alumnosDeLaPagina = alumnosFiltrados.slice(indiceInicial, indiceFinal);

    alumnosDeLaPagina.forEach(alumno => {
        const li = document.createElement('li');
        li.className = 'alumno-tutor-li';
        li.innerHTML = `
            <strong>${escapeHtml(alumno.nombre)}</strong>
            <a href="${baseUrlProgresos}${alumno.id_usuario}" class="btn-progreso-detallado">Ver Progreso Detallado</a>
        `;
        contenedorLista.appendChild(li);
    });

    renderizarControlesPaginacion(contenedorPaginacion);
}

function renderizarControlesPaginacion(contenedor) {
    if (!contenedor) return;
    contenedor.innerHTML = "";

    const totalPaginas = Math.ceil(alumnosFiltrados.length / limitePorPagina);
    if (totalPaginas <= 1) return; 

    for (let i = 1; i <= totalPaginas; i++) {
        const boton = document.createElement('button');
        boton.type = "button";
        boton.innerText = i;
        boton.className = `btn-pagina-num ${i === paginaActual ? 'pagina-activa' : ''}`;
        boton.onclick = function() {
            paginaActual = i;
            renderizarEstructuraLista();
            window.scrollTo({ top: contenedor.offsetTop - 400, behavior: 'smooth' });
        };
        contenedor.appendChild(boton);
    }
}

function escapeHtml(text) {
    const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
