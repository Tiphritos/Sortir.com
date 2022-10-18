// SOCIAL PANEL JS
const filter_btn = document.querySelector('.filter-btn');
const fclose_btn = document.querySelector('.fclose-btn');
const filters_panel_container = document.querySelector('.filters-panel-container');

filter_btn.addEventListener('click', () => {
    filters_panel_container.classList.toggle('visible')
},true);

fclose_btn.addEventListener('click', () => {
    filters_panel_container.classList.remove('visible')
},true);
