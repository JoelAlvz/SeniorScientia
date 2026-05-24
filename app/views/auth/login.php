<link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/login.css') ?>">

<div class="login-container-wrapper">
    
    <div class="login-context-column">
        <div class="context-overlay"></div> 
        <div class="context-content">
            <span class="project-tag">Proyecto Educativo</span>
            <img src="<?= base_url('img/logoLogin.png') ?>" alt="Logo SeniorScientia" class="project-logo">
            <p class="project-description">
                Una plataforma diseñada con cariño para romper la brecha digital. Ayudamos a nuestros mayores a dominar la tecnología a través de videojuegos interactivos, secuencias guiadas paso a paso y un seguimiento personalizado y cercano.
            </p>
            
            <div class="context-features">
                <div class="feature-item">
                    <span class="feature-icon">🎮</span>
                    <div>
                        <strong>Práctica Divertida</strong>
                        <p>Lecciones interactivas adaptadas mediante el juego.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">👨‍🏫</span>
                    <div>
                        <strong>Tutorización Directa</strong>
                        <p>Los tutores pueden guiar, enviar notas y medir el progreso real.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="login-form-column">
        <div class="form-box">
            <h2>¡Hola! Te damos la bienvenida</h2>
            <p class="form-subtitle">Por favor, introduce tus datos para acceder al panel.</p>

            <?php if (!empty($error)): ?>
                <div class="alert-error-custom">
                    <span class="alert-icon">⚠️</span>
                    <span class="alert-text"><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= base_url('login') ?>" id="form-login-profesional" novalidate>
                
                <div class="input-group-custom">
                    <label for="email">Correo Electrónico</label>
                    <div class="input-wrapper">
                        <span class="input-icon">✉️</span>
                        <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                    </div>
                    <span class="error-feedback" id="error-email"></span>
                </div>

                <div class="input-group-custom">
                    <label for="password">Contraseña</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password" name="password" placeholder="Tu contraseña" required>
                        <button type="button" class="btn-toggle-password" id="toggle-password-btn" onclick="togglePasswordVisibility()" aria-label="Mostrar contraseña">
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

                <button type="submit" class="btn-submit-professional">
                    <span>Iniciar Sesión</span>
                    <span class="btn-arrow">→</span>
                </button>
            </form>

            <p class="register-footer-link">
                ¿Aún no tienes una cuenta? <a href="<?= base_url('register') ?>">Regístrate aquí</a>
            </p>
        </div>
    </div>

</div>


<script src="<?= base_url('js/vistas/login.js') ?>"></script>