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
