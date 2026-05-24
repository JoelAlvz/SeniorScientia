// public/js/cursos_guiados.js

function cambiarSubSeccion(tipo) {
    const btnTeoria = document.getElementById('tab-teoria');
    const btnPractica = document.getElementById('tab-practica');
    const panelTeoria = document.getElementById('panel-teoria');
    const panelPractica = document.getElementById('panel-practica');

    if (tipo === 'teoria') {
        panelTeoria.style.display = 'block';
        panelPractica.style.display = 'none';

        btnTeoria.style.background = '#white';
        btnTeoria.style.color = '#007bff';
        btnTeoria.style.borderColor = '#007bff';
        btnPractica.style.background = '#f1f5f9';
        btnPractica.style.color = '#64748b';
        btnPractica.style.borderColor = '#cbd5e1';
    } else {
        panelTeoria.style.display = 'none';
        panelPractica.style.display = 'block';

        btnPractica.style.background = '#white';
        btnPractica.style.color = '#007bff';
        btnPractica.style.borderColor = '#007bff';
        btnTeoria.style.background = '#f1f5f9';
        btnTeoria.style.color = '#64748b';
        btnTeoria.style.borderColor = '#cbd5e1';

        const iframeTeclado = document.querySelector('#panel-practica iframe');
        
        if (iframeTeclado) {
            setTimeout(() => {
                iframeTeclado.contentWindow.focus();
                console.log("Foco enviado al juego del teclado con éxito.");
            }, 200);
        }
    }
}