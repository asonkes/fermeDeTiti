document.addEventListener('DOMContentLoaded', () => {
    // Sélectionner le conteneur de la grille et toutes les cartes
    const cards = document.querySelectorAll('.card__portrait');
    const products = document.querySelector('.products');
    
    // Fonction pour ajouter les classes `active` aux éléments progressivement
    function AppearCardPortrait() {
                cards.forEach((card) => {
                        const cardTitle = card.querySelector('.card__portrait-title');
                        const cardContent = card.querySelector('.card__portrait-content');
                        
                        // Ajouter la classe 'active' au titre et au contenu de la carte
                        if (cardTitle) {
                            cardTitle.classList.add('active');
                        }
                        if (cardContent) {
                            cardContent.classList.add('active');
                        }
                });
    }

    if (products) {
    // Écouter l'événement de défilement
    window.addEventListener('load', (e) => {
        e.preventDefault();
        AppearCardPortrait();
    });
    }
});