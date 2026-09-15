

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.toggleTheme = function () {
    const html = document.documentElement;
    const isLight = html.getAttribute('data-theme') === 'light';

    if (isLight) {
        html.removeAttribute('data-theme');
    } else {
        html.setAttribute('data-theme', 'light');
    }

    try {
        localStorage.setItem('xiands-theme', isLight ? 'dark' : 'light');
    } catch (e) {
        // Almacenamiento no disponible (modo privado, etc.) — el tema no persiste entre visitas.
    }
};
