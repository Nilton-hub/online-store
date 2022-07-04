// TOGGLE MENU
const btnMenu = document.querySelector('button.btn-menu');

const toggleMenu = evt => {
    if (evt.type == 'touchstart')
        evt.preventDefault();
    const nav = document.querySelector('nav.nav');

    nav.classList.toggle('active');
    const active = nav.classList.contains('active');
    evt.currentTarget.setAttribute('aria-expanded', active);

    if (active) {
        evt.currentTarget.setAttribute('aria-label', 'Fechar menu');
    } else {
        evt.currentTarget.setAttribute('aria-label', 'Abrir menu');
    }
}

if (btnMenu) {
    btnMenu.addEventListener('click', toggleMenu);
    btnMenu.addEventListener('touchstart', toggleMenu);
}

// FORM EFFECTS
const inputList = document.querySelectorAll('.form div input:not([type="submit"], [type="reset"]), .form div textarea')
      form = document.querySelector('form.form'),
      labelList = document.querySelectorAll('.form div label:not([for="input-remember"])'),
      resetLabel = () => {
          labelList.forEach((value, index) => {
              value.classList.remove('labelIn');
              value.classList.add('labelOut');
          });
          for (let i in form) if (!isNaN(i)) form[i].style.outline = 'none';
          if (document.querySelector('p#photo-name'))
            document.querySelector('p#photo-name').innerHTML = '';
      };

const inputReset = document.querySelector('input[type="reset"]');
if (inputReset) {
    inputReset.addEventListener('click', () => {
        resetLabel();
    });
}

// FORM SUBMIT
if (form) {
    form.addEventListener('submit', evt => {
        let error = false;
        for (let i in form) {
            if (isNaN(i)) {
                break;
            } else {
                if (document.querySelector(`.form div label[for="${form[i].getAttribute('id')}"] + i`)) {
                    var errorIcon = document.querySelector(`.form div label[for="${form[i].getAttribute('id')}"] + i`);
                    errorIcon.setAttribute('class', '');
                    errorIcon.setAttribute('style', '');
                }
                if (form[i].value.length === 0) {
                    form[i].focus();
                    form[i].style.outline = '2px solid rgba(255, 255, 255, .5)';
                    errorIcon.setAttribute('class', 'fas fa-exclamation-circle');
                    errorIcon.setAttribute('style', 'z-index: 3; left: 102%; top: 10px; color: rgb(213, 67, 67); position: absolute;');
                    error = true;
                    break;
                }
            }
            form[i].style.outline = 'none';
        }
        if (error) alert('Preencha todos os campos');
        else {
            alert('Cadastrado realizado com sucesso.');
            resetLabel();
            form.reset();
        }
    });
}