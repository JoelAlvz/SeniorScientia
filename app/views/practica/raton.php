<div style="text-align: center; padding: 20px; font-family: sans-serif;">
    <h2>¡Vamos a practicar con el ratón!</h2>
    <p style="font-size: 1.5rem;">Intenta tocar el <strong>círculo azul</strong> con la flecha de tu ratón.</p>
    
    <?php if(isset($_GET['meta'])): ?>
        <p style="color: #28a745; font-weight: bold; font-size: 1.2rem;">
            Meta del tutor: ¡Llega a <?= $_GET['meta'] ?> puntos para continuar!
        </p>
    <?php endif; ?>

    <h3 id="puntos" style="color: green;">Puntos: 0</h3>
    
    <div id="zona-juego" style="width: 100%; height: 400px; border: 3px dashed #ccc; position: relative; background: #f9f9f9; overflow: hidden; border-radius: 20px;">
        <div id="objetivo" style="
            width: 100px; 
            height: 100px; 
            background-color: #007bff; 
            border-radius: 50%; 
            position: absolute; 
            top: 50%; 
            left: 50%; 
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            transition: all 0.2s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 10;
        ">¡TÓCAME!</div>
    </div>

    <br>
    <a href="<?= base_url('dashboard') ?>" style="font-size: 1.2rem; color: #666;">Volver al menú</a>
</div>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const metaPuntos = parseInt(urlParams.get('meta')) || 0;
    const idActividad = urlParams.get('actividad');

    let puntos = 0;
    const objetivo = document.getElementById('objetivo');
    const marcador = document.getElementById('puntos');
    const zona = document.getElementById('zona-juego');

    function moverObjetivo() {
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

        // 2. Lógica de verificación de meta
        if (metaPuntos > 0 && puntos >= metaPuntos) {
            finalizarPasoDeActividad();
        }
    }

    function finalizarPasoDeActividad() {
        if (document.getElementById('btn-continuar')) return;

        const btn = document.createElement('a');
        btn.id = 'btn-continuar';
        btn.innerText = "¡META LOGRADA! PULSA AQUÍ PARA CONTINUAR";
        
        btn.href = "<?= base_url('actividad/avanzar/') ?>" + idActividad;
        
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
    
    objetivo.addEventListener('mouseover', moverObjetivo);
    objetivo.addEventListener('click', moverObjetivo);
</script>