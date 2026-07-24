document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', function (event) {
        const link = event.target.closest('a');
        if (!link) return;

        const message = link.getAttribute('data-confirm');
        if (message && !confirm(message)) {
            event.preventDefault();
            event.stopImmediatePropagation();
            return;
        }

        const method = link.getAttribute('data-method').toUpperCase();
        if (!method) {
            return;
        }

        event.preventDefault();

        const href = link.getAttribute('href');

        if (method === 'POST') {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = href;

            const csrfMeta = document.querySelector('meta[name="csrf"]');
            if (csrfMeta) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_csrf';
                csrfInput.value = csrfMeta.getAttribute('content');
                form.appendChild(csrfInput);
            }

            document.body.appendChild(form);
            form.submit();
        }
    });
});
