document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-registro-dinamico');
    const nombre = document.getElementById('nombre');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const progress = document.getElementById('registration-progress');

    const step2 = document.getElementById('step-2');
    const step3 = document.getElementById('step-3');
    const step4 = document.getElementById('step-4');

    const togglePasswordBtn = document.getElementById('toggle-password-btn');
    const eyeOpen = document.getElementById('svg-eye-open');
    const eyeClosed = document.getElementById('svg-eye-closed');

    let v1 = false, v2 = false, v3 = false;
    let emailTimeout;

    if (togglePasswordBtn && password && eyeOpen && eyeClosed) {
        togglePasswordBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Evitamos cualquier comportamiento extraño en el form
            e.stopPropagation();

            if (password.type === 'password') {
                password.type = 'text';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            } else {
                password.type = 'password';
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            }
        });
    }

    nombre.addEventListener('input', () => {
        if (nombre.value.trim().length >= 3 && !nombre.value.includes('@')) {
            limpiarError(nombre, 'error-nombre');
            if (!v1) {
                mostrarPaso(step2);
                actualizarProgreso(50);
                v1 = true;
            }
        } else {
            v1 = false;
            v2 = false;
            v3 = false;
            ocultarPasosDesde(2);
            actualizarProgreso(25);
            if (nombre.value.includes('@')) {
                mostrarError(nombre, 'error-nombre', '⚠️ Por favor, escribe tu nombre aquí, no tu correo.');
            }
        }
    });

    email.addEventListener('input', () => {
        if (!v1) {
            email.value = '';
            return;
        }

        const correoTexto = email.value.trim();
        clearTimeout(emailTimeout);

        if (validarFormatoEmail(correoTexto)) {
            emailTimeout = setTimeout(async () => {
                try {
                    const response = await fetch(`${BASE_URL}/verificar-email`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ email: correoTexto })
                    });

                    if (!response.ok) throw new Error("Error de servidor");
                    const data = await response.json();

                    if (data.existe) {
                        mostrarError(email, 'error-email', '❌ Este correo ya está registrado en la plataforma.');
                        v2 = false;
                        v3 = false;
                        ocultarPasosDesde(3);
                        actualizarProgreso(50);
                    } else {
                        limpiarError(email, 'error-email');
                        if (!v2) {
                            mostrarPaso(step3);
                            actualizarProgreso(75);
                            v2 = true;
                        }
                    }
                } catch (error) {
                    console.error(error);
                    mostrarError(email, 'error-email', '⚠️ Error al verificar el correo.');
                }
            }, 400);

        } else {
            v2 = false;
            v3 = false;
            ocultarPasosDesde(3);
            actualizarProgreso(50);
            if (correoTexto.length > 5) {
                mostrarError(email, 'error-email', 'Introduce un formato de correo válido.');
            }
        }
    });

    password.addEventListener('input', () => {
        if (!v2) {
            password.value = '';
            return;
        }

        if (password.value.length >= 4) {
            limpiarError(password, 'error-password');
            if (!v3) {
                mostrarPaso(step4);
                actualizarProgreso(100);
                v3 = true;
            }
        } else {
            v3 = false;
            ocultarPasosDesde(4);
            actualizarProgreso(75);
        }
    });

    form.addEventListener('submit', (e) => {
        if (!v1 || !v2 || !v3) {
            e.preventDefault();
            alert('Por favor, completa correctamente todos los pasos antes de registrarte.');
        }
    });

    function mostrarPaso(elemento) {
        elemento.style.opacity = '0';
        elemento.style.display = 'flex';
        setTimeout(() => {
            elemento.style.transition = 'opacity 0.4s ease';
            elemento.style.opacity = '1';
        }, 50);
    }

    function ocultarPasosDesde(paso) {
        if (paso <= 2) { step2.style.display = 'none'; }
        if (paso <= 3) { step3.style.display = 'none'; }
        if (paso <= 4) { step4.style.display = 'none'; }
    }

    function actualizarProgreso(porcentaje) {
        progress.style.width = `${porcentaje}%`;
    }

    function mostrarError(inputEl, idErrorSpan, mensaje) {
        const errorSpan = document.getElementById(idErrorSpan);
        if (errorSpan) {
            errorSpan.textContent = mensaje;
            errorSpan.style.display = 'block';
        }
        inputEl.classList.add('input-error-border');
    }

    function limpiarError(inputEl, idErrorSpan) {
        const errorSpan = document.getElementById(idErrorSpan);
        if (errorSpan) {
            errorSpan.textContent = '';
            errorSpan.style.display = 'none';
        }
        inputEl.classList.remove('input-error-border');
    }

    function validarFormatoEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
});