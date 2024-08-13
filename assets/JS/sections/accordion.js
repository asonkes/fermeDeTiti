document.addEventListener('DOMContentLoaded', () => {
    const accordionText = document.querySelectorAll('.accordion__text');
    console.log('accordionText', accordionText);

    const accordionActive = document.querySelector('.accordion__text-active');
    console.log('accordionActive', accordionActive);

    if(accordionActive) {
        const accordionRemove = document.querySelector('.accordion__remove');
        const accordionIcon = document.querySelector('.accordion__icon');

        accordionRemove.classList.toggle('active');
        accordionIcon.classList.toggle('active');
    }

    accordionText.forEach((e) => { 
        e.addEventListener('click', () => {
            const accordionRemove = e.querySelector('.accordion__remove');
            const accordionIcon = e.querySelector('.accordion__icon');

            accordionRemove.classList.toggle('active');
            accordionIcon.classList.toggle('active');
        })
    })
});