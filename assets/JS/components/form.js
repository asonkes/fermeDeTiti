document.addEventListener('DOMContentLoaded', () => {
    const formModified = document.getElementById('formModified');

    if(formModified ) {
        formModified.addEventListener('click', () => {
            const formParent = document.getElementById('formParent');

            const formCancel = document.getElementById('formCancel');
            
            const formValidate = document.getElementById('formValidate');
    
            formParent.classList.add('active');
            formCancel.classList.remove('active');
            formValidate.classList.remove('active');
        })
    }
})