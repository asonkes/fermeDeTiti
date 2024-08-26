document.addEventListener('DOMContentLoaded', () => {
    const icon = document.querySelector('.alert__message-icon');

    if (icon) {
        icon.addEventListener('click', () => {
            const flashMessage = document.querySelector('.alert__message-warning');
    
            flashMessage.classList.toggle('active');
        })
    }
}) 