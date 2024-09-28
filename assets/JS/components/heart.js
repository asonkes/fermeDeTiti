document.addEventListener('DOMContentLoaded', () => {
    const hearts = document.querySelectorAll('.iconHeart');

    hearts.forEach(heart => {
        heart.addEventListener('click', () => {
            heart.classList.add('active');
        })
    })
});