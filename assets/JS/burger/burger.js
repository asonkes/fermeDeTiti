document.addEventListener('DOMContentLoaded', () => {
    const burgerLink = document.querySelector('.burger__link');

    burgerLink.addEventListener('click', (e) => {
        e.preventDefault();

        const span = document.querySelector('.burger__span');
        const burgerMenu = document.querySelector('.burger__menu');
        const burger = document.querySelector('.burger');
        const burgerEffect = document.querySelector('.burger__effect');
        const body = document.querySelector('body');

        if(span) {
            span.classList.toggle('active');
            burgerMenu.classList.toggle('active');
            burger.classList.toggle('active');
            burgerEffect.classList.toggle('active');
            body.classList.toggle('active');
        }

        window.addEventListener('click', (e) => {
            if(burger.classList.contains('active') && !burger.contains(e.target) && !span.contains(e.target)) {
                span.classList.remove('active');
                burgerMenu.classList.remove('active');
                burger.classList.remove('active');
                burgerEffect.classList.remove('active');
                body.classList.remove('active');
            }
        })
    })
})