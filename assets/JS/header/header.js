document.addEventListener('DOMContentLoaded', () => {
    const linkSpecial = document.querySelector('.menu__link--special');
    console.log(linkSpecial, 'linkSpecial');

    const linkIcon = document.querySelector('.menu__link-icon');
    const menu = document.querySelector('.menu__submenu');
    const header = document.querySelector('.header');

    const home = document.querySelector('.home');
    const article = document.querySelector('.article');
    const products = document.querySelector('.products');

    linkSpecial.addEventListener('click', (e) => {
        e.preventDefault();
        if(menu) {
            menu.classList.toggle('active');
            
            if(!article) {
                header.classList.toggle('active');
            }
        
            linkIcon.classList.toggle('active');
        }
    })

    // Permet que le background du header reste même si on sort du menu
    header.addEventListener('click', () => {
        header.classList.add('active');
    })

    document.addEventListener('scroll', (e) => {
        e.preventDefault();

        const { scrollTop } = document.documentElement;

        if(header) {
            header.classList.add('active2');
            menu.classList.add('active2');  
           
        } else {
            header.classList.remove('active2');
            menu.classList.remove('active2');
        }

        if(scrollTop === 0) {
            header.classList.remove('active2');
            header.classList.add('active');
            menu.classList.remove('active2');
        }
    })

    // on load (Permet de mettre le background sur le header au chargement)
    if (article || products) {
        header.classList.add('active');
    }
    
    window.addEventListener('click', (event) => {
        // Si le menu est actif et que le clic ne vient ni du menu ni du lien, on ferme le menu
        if (menu.classList.contains('active') && !menu.contains(event.target) && !linkSpecial.contains(event.target)) {
            if (home) {
                header.classList.remove('active');
            }
            menu.classList.remove('active');
            linkIcon.classList.remove('active');
        }
    });
})