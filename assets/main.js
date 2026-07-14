document.addEventListener('DOMContentLoaded', () => {
    document.body.addEventListener('click', (event) => {
        const target = event.target.closest('[data-confirm]');
        
        if (target) {
            const message = target.getAttribute('data-confirm');
            
            if (!confirm(message)) {
                event.preventDefault();
                event.stopImmediatePropagation();
            }
        }
    });
});
