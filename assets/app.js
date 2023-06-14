import './styles/app.scss';
import './bootstrap';

window.addEventListener('load', () => {
    const openDrawerElements = document.querySelectorAll('[data-plugin="toggle"]');
    openDrawerElements.forEach(e => e.addEventListener('click', () => {
        const drawerElement = document.getElementById(e.dataset.target);
        drawerElement.classList.toggle('hidden');
    }));
})
