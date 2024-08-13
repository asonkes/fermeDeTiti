document.addEventListener('DOMContentLoaded', () => {
    const linkSpecial = document.querySelector('.menu__link--special');
    console.log(linkSpecial, 'linkSpecial');

    const linkIcon = document.querySelector('.menu__link-icon');
    const menu = document.querySelector('.menu__submenu');
    const header = document.querySelector('.header');
    const article = document.querySelector('.article');
    const menuLink = document.querySelectorAll('.menu__link');

    const headerCartImage = document.querySelector('.header__cart-image');
    const headerAccountImage = document.querySelector('.header__account-image');

    const headerCartImage2 = document.querySelector('.header__cart-image2');
    const headerAccountImage2 = document.querySelector('.header__account-image2');

    if(article) {
        menuLink.forEach((e) => {
            e.classList.add('active2');

            headerCartImage.classList.add('active');
            headerAccountImage.classList.add('active');
    
            headerCartImage2.classList.add('active');
            headerAccountImage2.classList.add('active');

            document.addEventListener('scroll', () => {
                e.classList.remove('active2');
                e.classList.add('active');

                headerCartImage.classList.remove('active');
                headerAccountImage.classList.remove('active');
    
                headerCartImage2.classList.remove('active');
                headerAccountImage2.classList.remove('active');
            })
        }) 
    }

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
            header.classList.remove('active2');
            header.classList.remove('active');
            menu.classList.remove('active');
            menu.classList.remove('active2');

            if(article) {
                menuLink.forEach((e) => {
                    e.classList.add('active2');
                }) 

                headerCartImage.classList.add('active');
                headerAccountImage.classList.add('active');
    
                headerCartImage2.classList.add('active');
                headerAccountImage2.classList.add('active');
            }
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