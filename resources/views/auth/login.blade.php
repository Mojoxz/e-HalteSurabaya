@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')
<div class="login-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="login-card">
                    <div class="login-header">
                        <div class="login-icon">
                            <img src="{{ asset('logo.svg') }}" alt="Logo E-HalteDishub" class="login-logo">
                        </div>
                        <h2 class="login-title">Login</h2>
                        <p class="login-subtitle">Masuk Untuk Pengalaman Lebih Baik</p>
                    </div>

                    <div class="login-body">
                        <form method="POST" action="{{ route('login') }}" class="login-form">
                            @csrf

                            <div class="input-group-wrapper">
                                <label for="email" class="input-label">
                                    <i class="fas fa-envelope"></i>
                                    Email Address
                                </label>
                                <div class="input-wrapper">
                                    <input
                                        type="email"
                                        class="form-input @error('email') input-error @enderror"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="email"
                                        autofocus
                                        placeholder="Masukkan email Anda">
                                    @error('email')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="input-group-wrapper">
                                <label for="password" class="input-label">
                                    <i class="fas fa-lock"></i>
                                    Password
                                </label>
                                <div class="input-wrapper password-wrapper">
                                    <input
                                        type="password"
                                        class="form-input password-input @error('password') input-error @enderror"
                                        id="password"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="Masukkan password Anda">
                                    <button type="button" class="password-toggle" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                    @error('password')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="checkbox-wrapper">
                                <label class="checkbox-label">
                                    <input
                                        type="checkbox"
                                        name="remember"
                                        id="remember"
                                        class="checkbox-input"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <span class="checkbox-custom"></span>
                                    <span class="checkbox-text">Ingat saya</span>
                                </label>
                            </div>

                            <button type="submit" class="login-button">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Login </span>
                            </button>
                        </form>
                    </div>

                    <div class="login-footer">
                       <!--<div class="footer-info">
                            <i class="fas fa-info-circle"></i>
                            <span>Hanya administrator yang dapat mengakses halaman ini</span>
                        </div> -->
                        <a href="{{ route('home') }}" class="back-link">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali ke Beranda</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk Toggle Password -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Password Function
    window.togglePassword = function() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        const toggleButton = document.querySelector('.password-toggle');

        if (!passwordInput || !toggleIcon || !toggleButton) {
            console.error('Password toggle elements not found');
            return;
        }

        // Add animation class to button
        toggleButton.classList.add('animating');

        // Add reveal animation to input
        passwordInput.classList.add('password-reveal-animation', 'animating');

        setTimeout(() => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');

                // Add a subtle shake animation when showing password
                passwordInput.style.animation = 'subtle-shake 0.5s ease-in-out';
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }, 150);

        // Remove animation classes after animation completes
        setTimeout(() => {
            toggleButton.classList.remove('animating');
            passwordInput.classList.remove('animating');
            passwordInput.style.animation = '';
        }, 600);

        // Add focus back to input after toggle
        setTimeout(() => {
            passwordInput.focus();
        }, 200);
    };

    // Initialize toggle button visibility
    const passwordInput = document.getElementById('password');
    const toggleButton = document.querySelector('.password-toggle');

    if (passwordInput && toggleButton) {
        // Set initial state
        toggleButton.style.opacity = passwordInput.value.length > 0 ? '1' : '0.6';

        // Update visibility based on input content
        passwordInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                toggleButton.style.opacity = '1';
                toggleButton.style.visibility = 'visible';
            } else {
                toggleButton.style.opacity = '0.6';
            }
        });
    }

    // Add keyboard shortcut (Ctrl/Cmd + Shift + H) to toggle password
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'H') {
            e.preventDefault();
            const passwordField = document.getElementById('password');
            if (document.activeElement === passwordField) {
                togglePassword();
            }
        }
    });
});
</script>

<style>
/* Variables untuk konsistensi dengan app.blade.php */
:root {
    --dishub-blue: #1a4b8c;
    --dishub-light-blue: #e6f0fa;
    --dishub-accent: #2a75d6;
    --dishub-dark-blue: #153a73;
    --gradient-primary: linear-gradient(135deg, var(--dishub-blue) 0%, var(--dishub-accent) 100%);
    --gradient-bg: linear-gradient(135deg, #f8fafc 0%, #e6f0fa 50%, #f0f6ff 100%);
    --shadow-light: 0 2px 8px rgba(26, 75, 140, 0.08);
    --shadow-medium: 0 4px 20px rgba(26, 75, 140, 0.12);
    --shadow-heavy: 0 8px 32px rgba(26, 75, 140, 0.16);
    --border-radius: 12px;
    --border-radius-lg: 16px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Reset dan base styles */
.login-wrapper {
    min-height: 100vh;
    background: var(--gradient-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    position: relative;
    overflow: hidden;
}

.login-wrapper::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(42, 117, 214, 0.05) 0%, transparent 70%);
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    33% { transform: translate(-2rem, -1rem) rotate(1deg); }
    66% { transform: translate(1rem, -2rem) rotate(-1deg); }
}

/* Card Styles */
.login-card {
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-heavy);
    overflow: hidden;
    width: 100%;
    max-width: 600px;
    position: relative;
    z-index: 1;
    border: 1px solid rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(10px);
}

/* Header Styles */
.login-header {
    background: var(--gradient-primary);
    padding: 3.5rem 3rem 3rem;
    text-align: center;
    position: relative;
}

.login-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
}

.login-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.75rem;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.login-logo {
    width: 48px;
    height: 48px;
    filter: brightness(0) invert(1);
}

.login-title {
    color: white;
    font-size: 2.25rem;
    font-weight: 700;
    margin: 0 0 0.75rem;
    letter-spacing: -0.025em;
}

.login-subtitle {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.1rem;
    margin: 0;
    font-weight: 400;
}

/* Body Styles */
.login-body {
    padding: 3rem;
}

.login-form {
    width: 100%;
}

/* Input Styles */
.input-group-wrapper {
    margin-bottom: 2rem;
}

.input-label {
    display: flex;
    align-items: center;
    font-weight: 600;
    color: var(--dishub-blue);
    font-size: 0.95rem;
    margin-bottom: 0.75rem;
    letter-spacing: 0.025em;
}

.input-label i {
    margin-right: 0.5rem;
    font-size: 0.95rem;
    opacity: 0.8;
}

.input-wrapper {
    position: relative;
}

.password-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.password-input {
    padding-right: 3.5rem !important;
}

.password-toggle {
    position: absolute;
    right: 0;
    top: 0;
    height: 100%;
    width: 3rem;
    background: none;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
    transition: var(--transition);
    border-radius: 0 var(--border-radius) var(--border-radius) 0;
    z-index: 2;
}

.password-toggle:hover {
    color: var(--dishub-accent);
    background: rgba(42, 117, 214, 0.05);
}

.password-toggle:focus {
    outline: none;
    color: var(--dishub-accent);
    background: rgba(42, 117, 214, 0.1);
}

.password-toggle i {
    font-size: 1rem;
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.password-toggle:hover i {
    transform: scale(1.1);
}

.password-toggle:active i {
    transform: scale(0.95);
}

.form-input {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid #e5e7eb;
    border-radius: var(--border-radius);
    font-size: 1rem;
    background: #fafbfc;
    transition: var(--transition);
    font-family: inherit;
}

.form-input:focus {
    outline: none;
    border-color: var(--dishub-accent);
    background: white;
    box-shadow: 0 0 0 4px rgba(42, 117, 214, 0.1);
    transform: translateY(-1px);
}

.form-input::placeholder {
    color: #9ca3af;
    font-weight: 400;
}

.form-input.input-error {
    border-color: #ef4444;
    background: #fef2f2;
}

.form-input.input-error:focus {
    border-color: #ef4444;
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
}

/* Password Animation Effects */
.password-reveal-animation {
    position: relative;
    overflow: hidden;
}

.password-reveal-animation::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(42, 117, 214, 0.1), transparent);
    transition: left 0.6s ease;
    pointer-events: none;
}

.password-reveal-animation.animating::before {
    left: 100%;
}

/* Icon Rotation Animation */
@keyframes iconFlip {
    0% { transform: rotateY(0deg) scale(1); }
    50% { transform: rotateY(90deg) scale(1.1); }
    100% { transform: rotateY(0deg) scale(1); }
}

.password-toggle.animating i {
    animation: iconFlip 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

/* Smooth Text Transition */
.password-input {
    transition: var(--transition), letter-spacing 0.3s ease;
}

.password-input[type="text"] {
    letter-spacing: normal;
}

.password-input[type="password"] {
    letter-spacing: 0.1em;
}

.error-message {
    display: flex;
    align-items: center;
    color: #ef4444;
    font-size: 0.8125rem;
    margin-top: 0.5rem;
    font-weight: 500;
}

.error-message i {
    margin-right: 0.375rem;
    font-size: 0.75rem;
}

/* Checkbox Styles */
.checkbox-wrapper {
    margin-bottom: 2rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 0.95rem;
    font-weight: 500;
    color: #374151;
}

.checkbox-input {
    display: none;
}

.checkbox-custom {
    width: 20px;
    height: 20px;
    border: 2px solid #d1d5db;
    border-radius: 4px;
    margin-right: 0.875rem;
    position: relative;
    background: white;
    transition: var(--transition);
    flex-shrink: 0;
}

.checkbox-input:checked + .checkbox-custom {
    background: var(--dishub-accent);
    border-color: var(--dishub-accent);
}

.checkbox-input:checked + .checkbox-custom::after {
    content: '';
    position: absolute;
    left: 5px;
    top: 2px;
    width: 4px;
    height: 8px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.checkbox-text {
    user-select: none;
}

/* Button Styles */
.login-button {
    width: 100%;
    background: var(--gradient-primary);
    color: white;
    border: none;
    border-radius: var(--border-radius);
    padding: 1.125rem 1.5rem;
    font-size: 1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: var(--shadow-medium);
    position: relative;
    overflow: hidden;
    margin-bottom: 1.25rem;
}

.login-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.login-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(26, 75, 140, 0.3);
}

.login-button:hover::before {
    left: 100%;
}

.login-button:active {
    transform: translateY(0);
}

.login-button i {
    margin-right: 0.5rem;
    font-size: 0.875rem;
}

/* Footer Styles */
.login-footer {
    background: #f8fafc;
    padding: 2rem 3rem;
    border-top: 1px solid #e5e7eb;
    text-align: center;
}

.footer-info {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 1.25rem;
    font-weight: 500;
}

.footer-info i {
    margin-right: 0.5rem;
    color: var(--dishub-accent);
}

.back-link {
    display: inline-flex;
    align-items: center;
    color: var(--dishub-accent);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: var(--transition);
    padding: 0.625rem 1rem;
    border-radius: 6px;
}

.back-link:hover {
    color: var(--dishub-dark-blue);
    background: rgba(42, 117, 214, 0.05);
    text-decoration: none;
}

.back-link i {
    margin-right: 0.5rem;
    font-size: 0.75rem;
    transition: var(--transition);
}

.back-link:hover i {
    transform: translateX(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .login-wrapper {
        padding: 1.5rem;
    }

    .login-header {
        padding: 2.5rem 2rem 2rem;
    }

    .login-body {
        padding: 2rem;
    }

    .login-footer {
        padding: 1.5rem 2rem;
    }

    .login-title {
        font-size: 1.875rem;
    }

    .login-subtitle {
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .login-wrapper {
        padding: 1rem;
    }

    .login-header {
        padding: 2rem 1.5rem 1.5rem;
    }

    .login-body {
        padding: 1.5rem;
    }

    .login-footer {
        padding: 1rem 1.5rem;
    }

    .login-title {
        font-size: 1.625rem;
    }

    .footer-info {
        font-size: 0.8rem;
    }

    .login-icon {
        width: 70px;
        height: 70px;
    }

    .login-logo {
        width: 40px;
        height: 40px;
    }
}

@media (max-width: 480px) {
    .input-group-wrapper {
        margin-bottom: 1.5rem;
    }

    .form-input {
        padding: 0.875rem 1rem;
        font-size: 0.95rem;
    }

    .password-input {
        padding-right: 3rem !important;
    }

    .password-toggle {
        width: 2.5rem;
    }

    .password-toggle i {
        font-size: 0.9rem;
    }

    .login-button {
        padding: 1rem;
        font-size: 0.95rem;
    }
}

/* Loading Animation */
.login-button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

/* Focus Styles for Accessibility */
.login-button:focus,
.back-link:focus,
.checkbox-label:focus-within {
    outline: 2px solid var(--dishub-accent);
    outline-offset: 2px;
}

/* Print Styles */
@media print {
    .login-wrapper {
        background: white;
        box-shadow: none;
    }

    .login-card {
        box-shadow: none;
        border: 1px solid #ddd;
    }

    .password-toggle {
        display: none;
    }
}

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    const toggleButton = document.querySelector('.password-toggle');

    // Add animation class to button
    toggleButton.classList.add('animating');

    // Add reveal animation to input
    passwordInput.classList.add('password-reveal-animation', 'animating');

    setTimeout(() => {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');

            // Add a subtle shake animation when showing password
            passwordInput.style.animation = 'none';
            passwordInput.offsetHeight; // Trigger reflow
            passwordInput.style.animation = 'subtle-shake 0.5s ease-in-out';
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }, 300);

    // Remove animation classes after animation completes
    setTimeout(() => {
        toggleButton.classList.remove('animating');
        passwordInput.classList.remove('animating');
        passwordInput.style.animation = '';
    }, 600);

    // Add focus back to input after toggle
    setTimeout(() => {
        passwordInput.focus();
    }, 100);
}

// Add subtle shake keyframe animation
const style = document.createElement('style');
style.textContent = `
    @keyframes subtle-shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-2px); }
        75% { transform: translateX(2px); }
    }

    @keyframes password-glow {
        0% { box-shadow: 0 0 0 4px rgba(42, 117, 214, 0); }
        50% { box-shadow: 0 0 0 4px rgba(42, 117, 214, 0.2); }
        100% { box-shadow: 0 0 0 4px rgba(42, 117, 214, 0); }
    }
`;
document.head.appendChild(style);

// Add keyboard shortcut (Ctrl/Cmd + Shift + H) to toggle password
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'H') {
        e.preventDefault();
        const passwordField = document.getElementById('password');
        if (document.activeElement === passwordField) {
            togglePassword();
        }
    }
});

// Add hover effect to password field when it contains text
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const toggleButton = document.querySelector('.password-toggle');

    passwordInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            toggleButton.style.opacity = '1';
            toggleButton.style.visibility = 'visible';
        } else {
            toggleButton.style.opacity = '0.6';
        }
    });

    // Initial state
    if (passwordInput.value.length === 0) {
        toggleButton.style.opacity = '0.6';
    }
});
</script>
</style>
@endsection
