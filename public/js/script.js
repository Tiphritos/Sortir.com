//Toggle

const chk = document.getElementById('chk');

chk.addEventListener('change', () => {
    document.body.classList.toggle('hub');
    document.getElementById("com").classList.toggle('titre')
});

// SOCIAL PANEL JS
const floating_btn = document.querySelector('.floating-btn');
const close_btn = document.querySelector('.close-btn');
const social_panel_container = document.querySelector('.social-panel-container');

floating_btn.addEventListener('click', () => {
    social_panel_container.classList.toggle('visible')
},false);

close_btn.addEventListener('click', () => {
    social_panel_container.classList.remove('visible')
},true);

//Barre de navigation
