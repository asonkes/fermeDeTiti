document.addEventListener('DOMContentLoaded', () => {

    const home = document.querySelector('.home');

    const header = document.querySelector('.header');
    const subMenu = document.querySelector('.menu__submenu');

    const linkSpecial = document.querySelector('.menu__link--special');
    const linkIcon = document.querySelector('.menu__link-icon');

    // Fonction pour initialiser l'état du header au chargement de la page
    function initializeheader() {
        if(!home) {
            header.classList.add('active');
        }
    }
 
    // Fonction pour gérer le scroll
    function handleScroll() {
        const { scrollTop } = document.documentElement;

        // Permet de changer la couleur du background du header
        header.classList.add('active2');

        // Permet de changer la couleur du background du sous-menu
        subMenu.classList.add('active2');  

        if(scrollTop === 0) {
            // Permet qd scroll=0, d'enlever le vert foncé du header
            header.classList.remove('active2');

            // Permet qd scroll=0, de remettre le vert clair du header
            header.classList.add('active');

            // Permet qd scroll=0, d'enlever le vert foncé du sous-menu
            subMenu.classList.remove('active2');
        }
    }

    // Fonction pour gérer le clic sur le lien spécial
    function toggleSubMenu(e) {
        e.preventDefault();
        if(subMenu) {
            // Permet d'ouvrir le sous-menu
            subMenu.classList.toggle('active');

            // Permet de faire un 'rotate' de l'icône à côté de "produits" 
            linkIcon.classList.toggle('active');
        }
    }

    // Fonction pour gérer le clic en dehors du sous-menu et du header
    function handleClickOutside(event) {
        // Permet que le background du header reste même si on sort du menu
        header.classList.add('active');
        
         // Si le menu est actif et que le clic ne vient ni du menu ni du lien, on ferme le menu
         if (subMenu.classList.contains('active') && !subMenu.contains(event.target) && !linkSpecial.contains(event.target)) {

            // Permet de fermer le sous-menu si clique en dehors du header
            subMenu.classList.remove('active');
            
            // Permet de remettre l'icône à son rotate de base si clique en dehors du header
            linkIcon.classList.remove('active');
        }
    }

    initializeheader();
    document.addEventListener('scroll', handleScroll);
    linkSpecial.addEventListener('click', toggleSubMenu);
    document.addEventListener('click', handleClickOutside);
})
