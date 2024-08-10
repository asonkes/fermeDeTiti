// JS permettant de faire pivoter le logo vers la droite ou la gauche en fonction de si on clique sur 'FR' ou 'NL'.

document.addEventListener('DOMContentLoaded', () => {
    const languageOptionLeft = document.querySelector('.home__language-optionLeft');
    const languageOptionRight = document.querySelector('.home__language-optionRight');
    const logo = document.querySelector('.home__logo-image');

    if(languageOptionLeft && languageOptionRight) {
        languageOptionLeft.addEventListener('mouseout', () => {
            if(logo) {
                logo.classList.remove('active');
            }
        })
    
        languageOptionRight.addEventListener('mouseout', () => {
            if(logo) {
                logo.classList.remove('active2');
            }
        })
    
        languageOptionLeft.addEventListener('mouseover', () => {
            if(logo) {
                logo.classList.add('active');
            }
        })
    
        languageOptionRight.addEventListener('mouseover', () => {
            if(logo) {
                logo.classList.add('active2');
            }
        })
    }
})