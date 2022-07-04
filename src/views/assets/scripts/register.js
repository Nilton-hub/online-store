// FORM EFFECTS
inputList.forEach((value, index) => {
    let id = value.getAttribute('id');
    let label = document.querySelector(`[for=${id}]`);
    setTimeout(() => {
        if (value.value != '' && label) label.classList.replace('labelOut', 'labelIn');
    }, 200);
    value.addEventListener('focus', evt => {
        label.classList.add('labelIn');
        label.classList.remove('labelOut');

        switch (evt.target.id) {
            case 'input-name':
                value.setAttribute('placeholder', `Como você quer ser chamado`);
                break;
            case 'input-email':
                value.setAttribute('placeholder', `exemplo@email.com`);
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
            value.setAttribute('placeholder', `Digite...`);
        }
    });

    value.addEventListener('blur', evt => {
        value.setAttribute('placeholder', ``)

        if (value.value.length === 0) {
            label.classList.remove('labelIn');
            label.classList.add('labelOut');
        } else value.style.outline = 'none';
    });
});

const password_re = document.querySelector('#input-password_re');
password_re.addEventListener('keyup', evt => {
    let msg = document.querySelector('#msgPassword');

    if (password_re.value != '' && form[3].value != '' && password_re.value != form[3].value) {
        msg.style.color = 'rgb(236, 72, 72)';
        msg.innerHTML = 'As senhas não correspondem';
    } else if(password_re.value != '' && form[3].value != '') {
        msg.style.color = '#FFD600';
        msg.innerHTML = 'Correspondencia';
    }
});

// FORM SUBMIT
form.addEventListener('submit', () => {
    submitForm('/registrar', {
        method: 'POST',
        body: new FormData(form)
    });
});
