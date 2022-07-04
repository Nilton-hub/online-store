// FORM EFFETCTS
inputList.forEach((value, index) => {
    let id = value.getAttribute('id');
    let label = document.querySelector(`[for=${id}]`);
    value.addEventListener('focus', evt => {
        label.classList.add('labelIn');
        label.classList.remove('labelOut');
        value.setAttribute('placeholder', `Digite...`);

        switch (evt.target.id) {
            case 'input-name':
                value.setAttribute('placeholder', `Nome do seu produto`);
                break;
            case 'text-desc':
                value.setAttribute('placeholder', `Detalhe o seu produto para as pessoas`);
                break;
            case 'input-price':
                value.setAttribute('placeholder', `0,00`);
                break;
            case 'input-fone':
                value.setAttribute('placeholder', `(00) 9 0000-0000`);
                break;
            case 'input-password':
                value.setAttribute('placeholder', `Crie uma senha`);
            break;
            case 'input-password_re':
                value.setAttribute('placeholder', `Confirme a senha`);
            break;
        default:
            console.log(evt.target.id);
        }
    });

    value.addEventListener('blur', evt => {
        value.setAttribute('placeholder', ``)

        if (value.value.length === 0) {
            label.classList.remove('labelIn');
            label.classList.add('labelOut');
        } else value.style.outline = 'none';
    });

    const inputImage = evt => {
        evt.stopPropagation();
        document.querySelector('p#photo-name').innerHTML = `<b>Foto:</b> <span class="photo-name">${evt.target.files[0].name}</span>`;
    }

    const inputFile = document.querySelector('#input-cover');
    inputFile.addEventListener('change', inputImage);    
});

//FORM SUBMIT
form.addEventListener('submit', () => {
    const formData = new FormData(form);
    loadBox.innerHTML = '<div id="load-icon-spinner"></div><p>Aguarde, carregando...</p>';
    loadBox.classList.remove('none');
    submitForm('/produto/novo', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            loadBox.innerHTML = '';
            loadBox.classList.add('none');
        })
        .catch(error => {
            console.error(error);
            loadBox.innerHTML = '';
            loadBox.classList.add('none');
        });
});
