

function confirmarDesasociacion(idAlumno, nombreAlumno) {
    const modal = document.getElementById('modal-confirmar-desasociar');
    const textoModal = document.getElementById('texto-confirmacion-modal');
    const inputId = document.getElementById('modal-id-alumno-input');

    if (modal && textoModal && inputId) {
        inputId.value = idAlumno;
        textoModal.innerHTML = `¿Estás seguro de que deseas desasociar al alumno <strong>${nombreAlumno}</strong>? Perderás el acceso directo a sus métricas de progreso de forma inmediata.`;
        
        modal.style.display = 'flex';
    }
}

function cerrarModalDesasociar() {
    const modal = document.getElementById('modal-confirmar-desasociar');
    const inputId = document.getElementById('modal-id-alumno-input');
    
    if (modal) {
        modal.style.display = 'none';
    }
    if (inputId) {
        inputId.value = '';
    }
}

window.addEventListener('click', function(event) {
    const modal = document.getElementById('modal-confirmar-desasociar');
    if (event.target === modal) {
        cerrarModalDesasociar();
    }
});