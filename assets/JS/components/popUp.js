document.addEventListener('DOMContentLoaded', () => {
    const popUpOpen = document.querySelector('.popUp');  // La pop-up entière
    const popUpClose = document.querySelector('.popUp__icon-close');  // L'icône de fermeture

    // Vérifier si l'URL contient le paramètre ?added=1
    const urlParams = new URLSearchParams(window.location.search);
    const productAdded = urlParams.get('added');

    if (productAdded === '1') {
        // Ouvre la pop-up si le produit a été ajouté
        popUpOpen.classList.add('active');
    }

    if(popUpClose) {
    // Fermer la pop-up au clic sur l'icône de fermeture (croix)
    popUpClose.addEventListener('click', () => {
        popUpOpen.classList.remove('active');
    });
    }

    // Fermer la pop-up en cliquant en dehors de la pop-up
    document.addEventListener('click', (event) => {
        if (popUpOpen.classList.contains('active') && !popUpOpen.contains(event.target)) {
            popUpOpen.classList.remove('active');
        }
    });
});