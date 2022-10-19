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
}, false);

close_btn.addEventListener('click', () => {
    social_panel_container.classList.remove('visible')
}, true);

document.getElementById("exemple").style.visibility = "hidden";

//Afficahge lieu


function affichage() {
    let affichage = document.getElementById("exemple");
    affichage.classList.toggle("visible");
    document.getElementById('eventPrevent').addEventListener('click', function () {

        let nomLieu = document.getElementById('lieu_nom_lieu').value;
        let rue = document.getElementById('lieu_rue').value;
        //Ville selectionnees
        let Selectville = document.getElementById('lieu_villes_no_ville');
        let VilleSelect = Selectville.options[Selectville.selectedIndex].text
        //Lieux selectionnes
        let lieuxSelectionnes = document.getElementById('sortie_lieux_no_lieu')

        fetch('/lieu/nouveau', {

            method: 'POST',
            body: JSON.stringify({nomLieu: nomLieu, rue: rue, ville: VilleSelect})
        })
            .then((response) => response.json())
            .then(data => {
                let newLieu = new Option(data[1], data[0]);
                //console.log(newLieu);
                lieuxSelectionnes.add(newLieu);
            });



}

)

/*/!*  *!/
   fetch('/sortie/new',{

       method: 'POST',
       body : JSON.stringify({nomLieu:'nom_lieu',rue:'rue',ville:'villes_no_ville' })
   }).then((response) => {
       return response.json()
   })
   ;*/
}

/*document.getElementById("eventPrevent").addEventListener("click",function(event){

  event.preventDefault();
})*/
/*

function example() {

   let  lieu = document.getElementById("exemple");
    lieu.style.visibility = lieu.style.visibility === "visible" ? "hidden" : "visible";
}
*/

//Pour le commit//