

document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const metaPuntos = parseInt(urlParams.get('meta')) || 0;
    const idActividad = urlParams.get('actividad');
    const base_url_php = document.body.getAttribute('data-base-url');

    let puntos = 0;
    const objetivo = document.getElementById('objetivo');
    const marcador = document.getElementById('puntos');
    const zona = document.getElementById('zona-juego');

  
    if (window.self !== window.top) {
        const titulo = document.getElementById('titulo-raton-juego');
        const mensajeMeta = document.getElementById('mensaje-meta-tutor');
        const btnVolver = document.getElementById('enlace-volver-menu');
        const instruccion = document.getElementById('texto-instruccion');

        if (titulo) titulo.style.display = 'none';
        if (mensajeMeta) mensajeMeta.style.display = 'none';
        if (btnVolver) btnVolver.style.display = 'none';
        
        if (instruccion) {
            instruccion.style.color = '#ffffff';
        }
    }

    function moverObjetivo() {
        objetivo.style.transform = "none";

        const maxX = zona.clientWidth - objetivo.clientWidth;
        const maxY = zona.clientHeight - objetivo.clientHeight;

        const posX = Math.floor(Math.random() * maxX);
        const posY = Math.floor(Math.random() * maxY);

        objetivo.style.left = posX + 'px';
        objetivo.style.top = posY + 'px';
        
        puntos++;
        marcador.innerText = "Puntos: " + puntos;

        objetivo.style.backgroundColor = "orange";
        setTimeout(() => { objetivo.style.backgroundColor = "#007bff"; }, 200);

        if (metaPuntos > 0 && puntos >= metaPuntos) {
            finalizarPasoDeActividad();
        }
    }

    function finalizarPasoDeActividad() {
        if (window.parent) {
            window.parent.postMessage("juego_completado", "*");
        }
        if (document.getElementById('btn-continuar')) return;

        const btn = document.createElement('a');
        btn.id = 'btn-continuar';
        btn.innerText = "¡META LOGRADA! PULSA AQUÍ PARA CONTINUAR";
        btn.href = base_url_php + "actividad/avanzar/" + idActividad;
        
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
    
    if (objetivo) {
        objetivo.addEventListener('mouseover', moverObjetivo);
        objetivo.addEventListener('click', moverObjetivo);
    }
});