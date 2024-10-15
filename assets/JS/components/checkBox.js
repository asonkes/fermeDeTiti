document.querySelectorAll('.order-status-toggle').forEach((checkbox) => {
    checkbox.addEventListener('change', function () {
        const isChecked = this.checked;
        const statusElement = this.closest('div').querySelector('.status');

        // Mettre à jour le texte de statut sans sauvegarder dans la base de données
        if (isChecked) {
            statusElement.textContent = 'Fait';
        } else {
            statusElement.textContent = 'A faire';
        }
    });
});