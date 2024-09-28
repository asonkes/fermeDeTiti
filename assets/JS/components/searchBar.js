document.addEventListener('DOMContentLoaded', () => {

    // Réinitialiser la barre de recherche à chaque chargement de la page
    const searchInput = document.getElementById('searchInput');
    searchInput.value = '';  // Effacer la valeur de la barre de recherche

    const pagination = document.querySelector('.pagination');

    if(searchInput) {
    // Écouter les frappes de touches dans la barre de recherche
    searchInput.addEventListener('keyup', () => {
        // Supprimer les espaces et mettre en minuscules la saisie
        // Ensuite, on enlève les accents en normalisant la chaîne
        const input = searchInput.value
            .trim()
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "");

        // Sélectionner tous les conteneurs de produits
        const products = document.querySelectorAll('.card__portrait');

        products.forEach(product => {
            // Sélectionner le nom du produit pour chaque carte
            const productTitleElement = product.querySelector('.card__portrait-title');
            
            // S'assurer que l'élément titre existe
            if (productTitleElement) {
                // Obtenir le texte du titre, enlever les accents et caractères spéciaux
                const productText = productTitleElement.textContent
                    .normalize("NFD")
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-zA-Z0-9 ]/g, '')
                    .toLowerCase()
                    .trim();

                // Vérifier si le texte du produit contient la valeur de l'entrée utilisateur
                if (productText.includes(input)) {
                    product.style.display = '';  // Afficher le produit
                } else {
                    product.style.display = 'none';  // Cacher le produit
                }
            }
        });
    });
    }

});