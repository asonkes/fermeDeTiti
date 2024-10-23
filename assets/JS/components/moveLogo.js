document.addEventListener('DOMContentLoaded', () => {
    const logo = document.querySelector('.home__logo-image'); // Sélectionner le logo

    if (logo) {
        // Écouter le mouvement de la souris sur toute la section home
        const home = document.querySelector('.home');

        home.addEventListener('mousemove', (event) => {
            const screenWidth = window.innerWidth; // Largeur de la fenêtre
            console.log('width' + screenWidth + 'px');
            
            const mouseX = event.clientX; // Position X de la souris par rapport à la fenêtre
            console.log('mouseX' + mouseX + 'px' );

            // Si la souris est dans la moitié gauche de l'écran
            if (mouseX < screenWidth / 2) {
                logo.classList.add('active2');  // Ajouter la classe pour le mouvement à gauche
                logo.classList.remove('active'); // Enlever la classe pour le mouvement à droite
            }
            // Si la souris est dans la moitié droite de l'écran
            else {
                logo.classList.add('active'); // Ajouter la classe pour le mouvement à droite
                logo.classList.remove('active2'); // Enlever la classe pour le mouvement à gauche
            }
        });
    }
});