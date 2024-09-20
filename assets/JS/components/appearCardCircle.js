document.addEventListener('DOMContentLoaded', () => {
    // Sélectionner le conteneur de la grille et toutes les cartes
    const grid = document.querySelector('.grid');
    const cards = document.querySelectorAll('.card__circle');
    const home = document.querySelector('.home');
    
    // Fonction pour ajouter les classes `active` aux éléments progressivement
    function appearCardCircle() {
        // Récupère la position actuelle du défilement et la hauteur de la fenêtre
        const { scrollTop, clientHeight } = document.documentElement;
        
        // Récupère la position du conteneur de la grille par rapport au haut de la fenêtre
        const topWindow = grid.getBoundingClientRect().top;
        
        // Vérifier si le conteneur de la grille est visible à plus de 50% dans la fenêtre
        if (scrollTop > (scrollTop + topWindow - clientHeight * 0.50)) {
                cards.forEach((card, index) => {
                    setTimeout(() => {
                        const cardTitle = card.querySelector('.card__circle-title');
                        const cardContent = card.querySelector('.card__circle-content');
                        
                        // Ajouter la classe 'active' au titre et au contenu de la carte
                        if (cardTitle) {
                            cardTitle.classList.add('active');
                        }
                        if (cardContent) {
                            cardContent.classList.add('active');
                        }
                    }, index * 200); // délai de 200ms entre chaque carte
                });
        }
    }

    if (home) {
    // Écouter l'événement de défilement
    window.addEventListener('scroll', (e) => {
        e.preventDefault();
        appearCardCircle();
    });

    window.addEventListener('load', (e) => {
        e.preventDefault();
        appearCardCircle();
    })
    }
});