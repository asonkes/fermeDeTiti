document.addEventListener('DOMContentLoaded', () => {
    const hearts = document.querySelectorAll('.iconHeart');
    console.log('hearts', hearts);

    hearts.forEach(heart => {
        heart.addEventListener('click', () => {
            heart.classList.add('active');
        })
    })
});