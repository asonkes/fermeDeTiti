document.addEventListener('DOMContentLoaded', () => {
    const icon = document.querySelector('.alert__message-icon');

    if (icon) {
        icon.addEventListener('click', () => {
            const flashMessage = document.querySelector('.alert__message-warning');

            flashMessage.classList.toggle('active');
        })
    }


    const icon2 = document.querySelector('.alert__message-icon2');

    if (icon2) {
        icon2.addEventListener('click', () => {
            const flashMessage2 = document.querySelector('.alert__message-warning--special');

            flashMessage2.classList.toggle('active');
        })
    }
}) 