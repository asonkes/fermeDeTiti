document.addEventListener('DOMContentLoaded', () => {
    const link = document.querySelector('.menu__link-special');
    const menu = document.querySelector('.menu__submenu');
    const header = document.querySelector('.header');

    console.log(link, 'link');
    
    link.addEventListener('click', () => {
        if(menu) {
            menu.classList.toggle('active');
            header.classList.toggle('active');
        } 
    })

    window.addEventListener('click', (event) => {
        // Si le menu est actif et que le clic ne vient ni du menu ni du lien, on ferme le menu
        if (menu.classList.contains('active') && !menu.contains(event.target) && !link.contains(event.target)) {
            menu.classList.remove('active');
            header.classList.remove('active');
        }
    });
})