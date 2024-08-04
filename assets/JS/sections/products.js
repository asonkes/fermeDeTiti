document.addEventListener('DOMContentLoaded', () => {
    // Sélectionner le conteneur de la grille et toutes les cartes
    const grid = document.querySelector('.home__grid-container');
    const cards = document.querySelectorAll('.home__card');
    
    // Fonction pour ajouter les classes `active` aux éléments progressivement
    function addActiveClassToElements() {
        // Récupère la position actuelle du défilement et la hauteur de la fenêtre
        const { scrollTop, clientHeight } = document.documentElement;
        
        // Récupère la position du conteneur de la grille par rapport au haut de la fenêtre
        const topWindow = grid.getBoundingClientRect().top;
        
        // Vérifier si le conteneur de la grille est visible à plus de 50% dans la fenêtre
        if (scrollTop > (scrollTop + topWindow - clientHeight * 0.50)) {
            cards.forEach((card, index) => {
                setTimeout(() => {
                    const cardTitle = card.querySelector('.home__card-title');
                    const cardContent = card.querySelector('.home__card-content');
                    
                    // Ajouter la classe `active` au titre et au contenu de la carte
                    if (cardTitle) {
                        cardTitle.classList.add('active');
                    }
                    if (cardContent) {
                        cardContent.classList.add('active');
                    }
                }, index * 250); // délai de 250ms entre chaque carte
            });
        }
    }

    // Écouter l'événement de défilement
    window.addEventListener('scroll', (e) => {
        e.preventDefault();
        addActiveClassToElements();
    });
});