import Alpine from 'alpinejs';

// Initialize Alpine.js
Alpine.start();

// Global utilities for forms and interactions
window.Alpine = Alpine;

// CSRF Token helper for form submissions
window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Flash message auto-dismiss
document.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('[data-alert-auto-dismiss]');
    alerts.forEach((alert) => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Delete confirmation handler
window.confirmDelete = (form) => {
    if (confirm('Are you sure you want to delete this record? This action cannot be undone.')) {
        form.submit();
    }
};
