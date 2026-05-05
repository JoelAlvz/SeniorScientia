
<h2>¡Hola! Vamos a configurar tu equipo</h2>
<p>Haz clic en la imagen que más se parezca a lo que ves cuando enciendes tu ordenador:</p>

<form action="<?= base_url('guardar-perfil') ?>" method="POST">
    <div class="opciones">
        <label>
            <input type="radio" name="so" value="windows10">
            <img src="/img/escritorio_w10.png" alt="Windows 10" width="200">
            <span>Mi pantalla tiene una barra azul abajo</span>
        </label>
        <label>
            <input type="radio" name="so" value="windows11">
            <img src="/img/escritorio_w11.png" alt="Windows 11" width="200">
            <span>Los iconos están en el centro abajo</span>
        </label>
    </div>
    <button type="submit">Siguiente</button>
</form>