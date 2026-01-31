document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password_hash');
    const loginError = document.getElementById('loginError');
    const loginErrorText = document.getElementById('loginErrorText');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');
    const btnLogin = document.querySelector('.btn-login');

    const BASE_URL = window.location.origin;

    if (togglePassword && eyeIcon && passwordInput) {
        togglePassword.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const currentType = passwordInput.getAttribute('type');
            const newType = currentType === 'password' ? 'text' : 'password';

            passwordInput.setAttribute('type', newType);

            if (newType === 'text') {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    }

    const inputs = document.querySelectorAll('.form-control-custom');
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            const icon = input.parentElement.querySelector('.input-icon');
            if (icon) {
                icon.style.transform = 'translateY(-50%) scale(1.05)';
            }
        });

        input.addEventListener('blur', () => {
            const icon = input.parentElement.querySelector('.input-icon');
            if (icon) {
                icon.style.transform = 'translateY(-50%) scale(1)';
            }
        });

        input.addEventListener('input', () => {
            if (loginError.style.display !== 'none') {
                loginError.style.display = 'none';
            }
        });
    });

    loginForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();

        if (!email || !password) {
            if (!email) shakeInput(emailInput);
            if (!password) shakeInput(passwordInput);
            showError('Please fill in all fields');
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            shakeInput(emailInput);
            showError('Please enter a valid email address');
            return;
        }

        btnLogin.classList.add('loading');
        btnLogin.disabled = true;

        try {
            const response = await fetch(`${BASE_URL}/api/admin/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                }),
                credentials: 'include' 
            });

            const data = await response.json();

            if (data.status && data.access_token) {
                setCookie('access_token', data.access_token, 1 / 96); 
                setCookie('refresh_token', data.refresh_token, 30); 

                if (data.user) {
                    localStorage.setItem('user', JSON.stringify(data.user));
                }
                setTimeout(() => {
                    window.location.href = `${BASE_URL}/api/admin/dashboard`;
                }, 100);
            } else {
                btnLogin.classList.remove('loading');
                btnLogin.disabled = false;

                showError(data.message || 'Invalid email or password');
                shakeInput(emailInput);
                shakeInput(passwordInput);
            }
        } catch (err) {
            console.error('Login error:', err);

            btnLogin.classList.remove('loading');
            btnLogin.disabled = false;

            showError('Connection error. Please try again.');
        }
    });

    function shakeInput(input) {
        input.classList.add('error-shake');
        setTimeout(() => input.classList.remove('error-shake'), 500);
    }

    function showError(message) {
        loginErrorText.textContent = message;
        loginError.style.display = 'flex';

        setTimeout(() => {
            loginError.style.display = 'none';
        }, 5000);
    }

    function setCookie(name, value, days) {
        const d = new Date();
        d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + d.toUTCString();
        document.cookie = `${name}=${value};${expires};path=/;SameSite=Lax`;
    }

    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    const accessToken = getCookie('access_token');
    if (accessToken) {
        window.location.href = `${BASE_URL}/api/admin/dashboard`;
    }

    passwordInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            loginForm.dispatchEvent(new Event('submit'));
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && loginError.style.display !== 'none') {
            loginError.style.display = 'none';
        }
    });
});