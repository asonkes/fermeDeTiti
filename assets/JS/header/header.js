document.addEventListener('DOMContentLoaded', () => {
    const linkMenu = document.querySelectorAll('.menu__link, .menu__submenu-link');
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
        if(header) {
            header.classList.add('active2');
            menu.classList.add('active2');  
            linkMenu.forEach((e) => {
                e.classList.add('active');
            });      
        } else {
            header.classList.remove('active2');
            menu.classList.remove('active2');
            linkMenu.forEach((e) => {
                e.classList.remove('active');
            });  
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