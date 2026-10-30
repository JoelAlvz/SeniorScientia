<link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/register.css') ?>">

<div class="login-container-wrapper">
    
    <div class="login-context-column">
        <div class="context-overlay"></div>
        <div class="context-content">
            <span class="project-tag">Proyecto Educativo</span>
            <img src="<?= base_url('img/logoLogin.png') ?>" alt="Logo SeniorScientia" class="project-logo">
            <p class="project-description">
                Únete hoy a nuestra comunidad. Estás a unos pocos pasos de comenzar una aventura interactiva adaptada para aprender tecnología sin frustraciones.
            </p>
        </div>
    </div>

    <div class="login-form-column">
        <div class="form-box">
            <h2>Crear una cuenta</h2>
            <p class="form-subtitle">Completa los datos para darte de alta.</p>

            <div class="registration-progress-bar">
                <div class="progress-line" id="registration-progress"></div>
            </div>

            <form action="<?= base_url('register') ?>" method="POST" id="form-registro-dinamico" novalidate>                
                <div class="input-group-custom step-group active" id="step-1">
                    <label for="nombre">¿Cómo te llamas?</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input type="text" id="nombre" name="nombre" placeholder="Nombre completo" required>
                    </div>
                    <span class="error-feedback" id="error-nombre"></span>
                </div>

                <div class="input-group-custom step-group" id="step-2" style="display: none;">
                    <label for="email">Tu Correo Electrónico</label>
                    <div class="input-wrapper">
                        <span class="input-icon">✉️</span>
                        <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                    </div>
                    <span class="error-feedback" id="error-email"></span>
                </div>

                <div class="input-group-custom step-group" id="step-3" style="display: none;">
                    <label for="password">Crea una Contraseña</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password" name="password" placeholder="Mínimo 4 caracteres" required>
                        <button type="button" class="btn-toggle-password" id="toggle-password-btn" aria-label="Mostrar contraseña">
                            <svg id="svg-eye-open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-svg-pro">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg id="svg-eye-closed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-svg-pro" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    <span class="error-feedback" id="error-password"></span>
                </div>

                <div class="input-group-custom step-group" id="step-4" style="display: none;">
                    <label for="tipo">¿Cómo vas a usar la plataforma?</label>
                    <div class="input-wrapper">
                        <select name="tipo" id="tipo" required>
                            <option value="alumno">Soy Alumno (Quiero aprender)</option>
                            <option value="tutor">Soy Tutor (Quiero enseñar)</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-submit-professional">
                        <span>Crear cuenta</span>
                        <span class="btn-arrow">✓</span>
                    </button>
                </div>
            </form>

            <p class="register-footer-link">
                ¿Ya tienes una cuenta? <a href="<?= base_url('login') ?>">Inicia sesión</a>
            </p>
        </div>
    </div>

</div>
<script>
    const BASE_URL = "<?= base_url() ?>"; 
</script>
<script src="<?= base_url('js/vistas/register.js') ?>"></script>