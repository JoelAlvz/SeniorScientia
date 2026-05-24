document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-login-profesional');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    form.addEventListener('submit', (e) => {
        let esValido = true;

        if (!emailInput.value.trim()) {
            mostrarError(emailInput, 'error-email', 'El correo electrónico es obligatorio.');
            esValido = false;
        } else if (!validarFormatoEmail(emailInput.value)) {
            mostrarError(emailInput, 'error-email', 'Introduce un formato de correo válido.');
            esValido = false;
        } else {
            limpiarError(emailInput, 'error-email');
        }

        if (!passwordInput.value.trim()) {
            mostrarError(passwordInput, 'error-password', 'La contraseña es obligatoria.');
            esValido = false;
        } else {
            limpiarError(passwordInput, 'error-password');
        }

        if (!esValido) {
            e.preventDefault();
        }
    });

    function mostrarError(inputEl, idErrorSpan, mensaje) {
        const errorSpan = document.getElementById(idErrorSpan);
        errorSpan.textContent = mensaje;
        errorSpan.style.display = 'block';
        inputEl.classList.add('input-error-border');
    }

    function limpiarError(inputEl, idErrorSpan) {
        const errorSpan = document.getElementById(idErrorSpan);
        errorSpan.textContent = '';
        errorSpan.style.display = 'none';
        inputEl.classList.remove('input-error-border');
    }

    function validarFormatoEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
});

function togglePasswordVisibility() {
    const passInput = document.getElementById('password');
    const eyeOpen = document.getElementById('svg-eye-open');
    const eyeClosed = document.getElementById('svg-eye-closed');
    
    if (passInput.type === 'password') {
        passInput.type = 'text';
        eyeOpen.style.display = 'none';      
        eyeClosed.style.display = 'block';   
    } else {
        passInput.type = 'password';
        eyeOpen.style.display = 'block';    
        eyeClosed.style.display = 'none';     
    }
}