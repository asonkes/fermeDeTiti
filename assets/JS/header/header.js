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
})