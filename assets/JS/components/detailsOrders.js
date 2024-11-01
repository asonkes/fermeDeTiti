document.addEventListener('DOMContentLoaded', () => {
    const detailsOrdersJS = document.querySelectorAll('.detailsOrdersJS');

    detailsOrdersJS.forEach((e) => {
        e.addEventListener('click', () => {
            const accordionTextContainer = e.closest('.accordion__textJS');

            if (accordionTextContainer) {
                // Cible uniquement les éléments à l'intérieur de cet accordionTextContainer
                const detailsContainer = accordionTextContainer.querySelector('.detailsOrdersJS--container');
                const accordionRemove = accordionTextContainer.querySelector('.accordion__remove');
                const accordionIcon = accordionTextContainer.querySelector('.accordion__icon');

                // Permet que le conteneur contenant les informations du produit s'ouvre.
                detailsContainer.classList.toggle('active');
                // Permet que le bloc de "l'accordion" ne se referme pas qd on clique sur le bouton
                accordionRemove.classList.toggle('active');
                // Permet que l'icône de "laccordion" reste vers le bas
                accordionIcon.classList.toggle('active');
            }
        });
    });
});