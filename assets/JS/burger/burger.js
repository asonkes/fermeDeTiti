document.addEventListener('DOMContentLoaded', () => {
    const burgerLink = document.querySelector('.burger__link');
    console.log(burgerLink, 'burgerLink');

    burgerLink.addEventListener('click', (e) => {
        e.preventDefault();

        const span = document.querySelector('.span');
        const burgerMenu = document.querySelector('.burger__menu');
        const burger = document.querySelector('.burger');
        const home = document.querySelector('.home');

        if(span) {
            span.classList.toggle('active');
            burgerMenu.classList.toggle('active');
            burger.classList.toggle('active');
            home.classList.toggle('active');
        }

        window.addEventListener('click', (e) => {
            if(burger.classList.contains('active') && !burger.contains(e.target) && !span.contains(e.target)) {
                span.classList.remove('active');
                burgerMenu.classList.remove('active');
                burger.classList.remove('active');
                home.classList.remove('active');
            }
        })
    })
})