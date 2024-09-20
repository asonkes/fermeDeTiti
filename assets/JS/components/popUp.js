document.addEventListener('DOMContentLoaded', () => {
    const addProduct = document.querySelector('.addProduct');  // Le bouton "Ajouter au panier"
    const popUpOpen = document.querySelector('.popUp');        // La pop-up entière
    const popUpClose = document.querySelector('.popUp__icon-close');  // L'icône de fermeture

    // Ouvrir la pop-up au clic sur "Ajouter au panier"
    addProduct.addEventListener('click', (event) => {
        event.preventDefault();  // Empêche le comportement par défaut du lien
        popUpOpen.classList.add('active');  // Affiche la pop-up
    });

    // Fermer la pop-up au clic sur l'icône de fermeture (croix)
    popUpClose.addEventListener('click', () => {
        popUpOpen.classList.remove('active');  // Ferme la pop-up
    });

    // Fermer la pop-up en cliquant en dehors de la pop-up et du bouton "Ajouter au panier"
    document.addEventListener('click', (event) => {
        // Donc on retire la possibilité de clic sur le bouton de départ qui ouvre la popUp
        if (popUpOpen.classList.contains('active') && 
            !popUpOpen.contains(event.target) && 
            event.target !== addProduct) {
            popUpOpen.classList.remove('active');  // Ferme la pop-up
        }
    });
});