document.querySelector('button.btn-menu').addEventListener('click', evt => {
    document.querySelector('nav.nav ul').classList.toggle('slide-down');
    document.querySelector('nav.nav ul').classList.toggle('slide-up');
});

if (inputList) {
    inputList.forEach((value, index) => {
        let id = value.getAttribute('id');
        let label = document.querySelector(`[for=${id}]:not([for="input-remember"])`);
        setTimeout(() => {
            if (value.value != '' && label) label.classList.replace('labelOut', 'labelIn');
        }, 200);
        value.addEventListener('focus', evt => {
            if (label) {
                label.classList.add('labelIn');
                label.classList.remove('labelOut');
                value.setAttribute('placeholder', `Digite...`);

                switch (evt.target.id) {
                    case 'input-email':
                        value.setAttribute('placeholder', `Informe o seu email`);
                    break;
                    case 'input-password':
                        value.setAttribute('placeholder', `Informe a sua senha`);
                    break;
                default:
                    console.log(evt.target.id);
                }
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
}

// SHOW / HIDE PASSWORD
const iconEye = document.querySelector('span.icon-simbol-right');
let showPass = true;
const toggleEye = (evt) => {
    const inputPass = document.querySelector('#input-password');
    if (showPass) {
        iconEye.innerHTML = `<i class="fas fa-eye"></i>`;
        inputPass.setAttribute('type', 'text');
        showPass = false;
    } else {
        iconEye.innerHTML = `<i class="fas fa-eye-slash" id="iconEye"></i>`;
        inputPass.setAttribute('type', 'password');
        showPass = true;
    }
};
iconEye.addEventListener('click', toggleEye);

// SUBMIT FORM
form.addEventListener('submit', evt => {
    loadBox.innerHTML = '<div id="load-icon-spinner"></div><p>Aguarde, carregando...</p>';
    loadBox.classList.remove('none');
    const formData = new FormData(form);
    const data = submitForm('/entrar', {
        method: 'POST',
        body: formData
    });
    
    loadBox.classList.add('none');
    loadBox.innerHTML = '';
});
