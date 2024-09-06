document.addEventListener('DOMContentLoaded', () => {
    const imageInput = document.querySelector('.input__imageJS');
    console.log(imageInput,'imageInput');

    const previewImage = document.querySelector('.preview__image');

    if(imageInput) {
        imageInput.addEventListener('change', (e) => {
            const file = e.target.files[0]; // Récupérer le fichier sélectionné

            if (file) {
                // Crée une URL de l'image pour l'aperçu
                const imageUrl = URL.createObjectURL(file);

                // Affiche l'image dans le conteneur
                previewImage.innerHTML = `<img class="preview__image-img" src="${imageUrl}" alt="Aperçu" >`;
            }

            previewImage.classList.add('active');
        })
    }
})