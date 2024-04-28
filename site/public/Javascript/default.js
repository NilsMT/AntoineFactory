// show password button
const passwordInput = document.querySelectorAll('.passwordinput>img');

passwordInput.forEach(element => {
    element.addEventListener('mousedown', function () {
        element.classList.remove('jaune');
        element.classList.add('noir');
        element.src = '../assets/icons/hide.png';
        element.previousElementSibling.type = 'text';
    });
    element.addEventListener('mouseup', function () {
        element.classList.remove('noir');
        element.classList.add('jaune');
        element.src = '../assets/icons/show.png';
        element.previousElementSibling.type = 'password';
    });
});

// info button
// const infos = document.querySelectorAll('.phoneinput>img');
// infos.forEach(info => {
//     info.addEventListener('click', function () {
//         window.alert(info.getAttribute('data-info'));
//     });
// });

//hitbox
const footerDivs = document.querySelectorAll("footer > div");
const hitboxes = document.querySelectorAll("*");
var click=false

footerDivs.forEach(div => {
    if (div.textContent.includes("|")) {
        div.addEventListener("click", function () {
            console.warn("clicked");
              click= !click
              //affiche les hitbox ou non
              hitboxes.forEach(hitbox => {
                if (click==true && hitbox.style.border=="") {
                    hitbox.style.border = "0.1px solid #00ffff";
                } else {
                    hitbox.style.border = "";
                }
            });
        });
    }
});

