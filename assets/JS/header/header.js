document.addEventListener('DOMContentLoaded', () => {
    const linkSpecial = document.querySelector('.menu__link--special');
    const linkIcon = document.querySelector('.menu__link-icon');
    const menu = document.querySelector('.menu__submenu');
    const header = document.querySelector('.header');

    console.log(linkSpecial, 'linkSpecial');
    
    linkSpecial.addEventListener('click', (e) => {
        e.preventDefault();
        if(menu) {
            menu.classList.toggle('active');
            header.classList.toggle('active');
            linkIcon.classList.toggle('active');
        }
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
            header.classList.add('active');
            header.classList.remove('active2');
        }
    })
    
    window.addEventListener('click', (event) => {
        // Si le menu est actif et que le clic ne vient ni du menu ni du lien, on ferme le menu
        if (menu.classList.contains('active') && !menu.contains(event.target) && !linkSpecial.contains(event.target)) {
            menu.classList.remove('active');
            header.classList.remove('active');
            linkIcon.classList.remove('active');
        }
    });
})