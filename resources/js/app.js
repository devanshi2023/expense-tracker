import './bootstrap';

document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
    toggle.addEventListener('click', () => {
        const field = toggle.closest('.password-field')?.querySelector('[data-password-input]');

        if (!field) {
            return;
        }

        const isHidden = field.type === 'password';

        field.type = isHidden ? 'text' : 'password';
        toggle.textContent = isHidden ? 'Hide' : 'Show';
        toggle.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
    });
});

document.querySelectorAll('[data-approval-confirm]').forEach((button) => {
    button.addEventListener('click', (event) => {
        const message = button.getAttribute('data-approval-confirm');

        if (!message) {
            return;
        }

        if (!window.confirm(message)) {
            event.preventDefault();
        }
    });
});
