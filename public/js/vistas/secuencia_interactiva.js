/* ==========================================================================
   Lógica JavaScript exclusiva para ver_secuencia.php
   ========================================================================== */

function validarClickBloqueado(e) {
    if (!metaSuperada) {
        e.preventDefault(); 
        return false;
    }
    return true;
}

function activarBotonMetaLograda() {
    if (metaSuperada) return; 
    metaSuperada = true; 

    const cartelExito = document.getElementById("cartel-meta-exito");
    if (cartelExito) {
        cartelExito.style.display = "block";
    }

    const botonAvanzar = document.getElementById("btn-avanzar-secuencia");
    if (botonAvanzar) {
        botonAvanzar.style.background = "#16a34a"; 
        botonAvanzar.style.color = "#ffffff";
        botonAvanzar.style.cursor = "pointer";
        botonAvanzar.style.opacity = "1";
        botonAvanzar.style.boxShadow = "0 6px 15px rgba(22, 163, 74, 0.3)";
        
        const textoMeta = botonAvanzar.getAttribute('data-texto-meta');
        if (textoMeta) {
            botonAvanzar.innerHTML = textoMeta;
        }
    }
}

window.addEventListener("message", function(event) {
    if (event.data === "juego_completado" || event.data === "meta_lograda") {
        activarBotonMetaLograda();
    }
});

document.addEventListener("DOMContentLoaded", function() {
    const iframe = document.getElementById('iframe-juego');
    if (iframe) {
        iframe.addEventListener('load', function() {
            try {
                const innerDoc = iframe.contentDocument || iframe.contentWindow.document;
                
                const fraseDuplicada = innerDoc.querySelector('h2');
                if (fraseDuplicada && fraseDuplicada.innerText.includes('practicar con el ratón')) {
                    fraseDuplicada.style.display = 'none';
                }

                const estiloInyectado = innerDoc.createElement('style');
                estiloInyectado.innerHTML = `
                    a[href*="dashboard"], .btn-volver, header, footer, .dashboard-header { 
                        display: none !important; 
                    }
                    body, html, #game-container, canvas, .container, #canvas { 
                        width: 100% !important; 
                        max-width: 100% !important; 
                        height: 100% !important;
                        margin: 0 auto !important; 
                        padding: 0 !important; 
                        overflow: hidden !important;
                    }
                    #zona-juego {
                        width: 100% !important;
                        height: calc(100vh - 120px) !important;
                        min-height: 320px !important;
                    }
                    #btn-continuar, .btn-meta-lograda, .modal-meta {
                        display: none !important;
                    }
                `;
                innerDoc.head.appendChild(estiloInyectado);
            } catch (e) {
                console.log("Restricción de iframe controlada (origen cruzado o entorno local).");
            }
        });
    }
});