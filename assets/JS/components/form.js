document.addEventListener('DOMContentLoaded', () => {
    const buttonModified = document.getElementById('buttonModified');
    const form = document.getElementById('form');
    const buttonCancel = document.getElementById('buttonCancel');
    const buttonValidate = document.getElementById('buttonValidate');

    if (buttonModified) {
        buttonModified.addEventListener('click', () => {
            form.classList.add('active'); // Ouvre le formulaire
            buttonCancel.classList.remove('active'); // Cache le bouton Annuler
            buttonValidate.classList.remove('active'); // Cache le bouton Valider
        });
    }

    // Optionnel : Si le formulaire est soumis avec des erreurs, assurez-vous qu'il reste ouvert
    const errorMessage = document.querySelector('.error-message');
    if (errorMessage) {
        form.classList.add('active'); // Assurez-vous que le formulaire est actif si des erreurs sont présentes
    }
});