document.addEventListener('DOMContentLoaded', () => {
    const imageInput = document.querySelector('.input__imageJS');
    const previewImage = document.querySelector('.preview__image');
    const currentImage = document.querySelector('.current__image');
    const currentText = document.querySelector('.current__text');

    if (imageInput && previewImage) {
        imageInput.addEventListener('change', (e) => {
            const file = e.target.files[0]; // Récupérer le fichier sélectionné

            if (file) {
                // Crée une URL de l'image pour l'aperçu
                const imageUrl = URL.createObjectURL(file);

                // Affiche la nouvelle image dans le conteneur
                previewImage.innerHTML = `<img class="preview__image-img" src="${imageUrl}" alt="Aperçu" width='200'>`;

                // Masquer l'ancienne image et afficher la nouvelle
                if (currentImage) {
                    currentImage.classList.add('active');
                }

            } else {
                // Si aucun fichier n'est sélectionné, affiche l'ancienne image
                if (currentImage) {
                    currentImage.classList.remove('active'); // Afficher l'ancienne image
                }
                previewImage.innerHTML = ''; // Effacer l'aperçu
            }

            previewImage.classList.add('active');

            if(currentText) {
 currentText.classList.add('active');
            }
        });
    }
});