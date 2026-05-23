function confirmarDesasociacion(idAlumno, nombreAlumno) {
    const modal = document.getElementById('modal-confirmar-desasociar');
    const textoModal = document.getElementById('texto-confirmacion-modal');
    const inputId = document.getElementById('modal-id-alumno-input');

    if (modal && textoModal && inputId) {
        // Asignamos dinámicamente los datos al formulario del modal
        inputId.value = idAlumno;
        textoModal.innerHTML = `¿Estás seguro de que deseas desasociar al alumno <strong>${nombreAlumno}</strong>? Perderás el acceso directo a sus métricas de progreso de forma inmediata.`;
        
        // Mostramos el modal
        modal.style.display = 'flex';
    }
}

function cerrarModalDesasociar() {
    const modal = document.getElementById('modal-confirmar-desasociar');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Cerrar si se hace clic fuera de la ventana blanca
window.addEventListener('click', function(event) {
    const modal = document.getElementById('modal-confirmar-desasociar');
    if (event.target === modal) {
        cerrarModalDesasociar();
    }
});