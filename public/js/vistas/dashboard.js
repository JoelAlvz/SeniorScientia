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

// Opcional: Cierra el modal si el usuario hace clic fuera de la caja blanca
window.addEventListener('click', function(event) {
    const modal = document.getElementById('modal-tareas-anteriores');
    if (event.target === modal) {
        cerrarModalTareas();
    }
});
