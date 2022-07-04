const btnMenu = document.querySelector('button.btn-menu'),
    userMsg = document.querySelector('div.message'),
    iconMsg = userMsg.querySelector('#msg-icon'),
    formSearch = document.querySelector('.form-search'),
    labelList = document.querySelectorAll('.form div label:not([for="input-remember"])'),
    loadBox = document.querySelector('#load-box-animate'),
    formater = Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });

const renderMsg = (text, type = null) => {
    userMsg.querySelector('p').innerHTML = text;

    const types = ['alert', 'success', 'error', 'info'];
    for (let e of types) userMsg.classList.remove(e);
    userMsg.classList.add(type);

    switch (type) {
        case 'alert':
            iconMsg.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
            break;
        case 'success':
            iconMsg.innerHTML = '<i class="fas fa-check-circle"></i>';
            break;
        case 'error':
            iconMsg.innerHTML = '<i class="fas fa-bomb"></i>';
            break;
        case 'info':
            iconMsg.innerHTML = '<i class="fas fa-info-circle"></i>';
            break;
        default:
            iconMsg.innerHTML = '<i class="fas fa-comment-dots"></i>';
            break;
    }
    userMsg.style.display = 'flex';
}

const redirectHome = () => window.location.href = 'http://localhost/projects/online-store/';

document.getElementsByName('search')[0].addEventListener('keyup', evt => {
    const formData = new FormData();
    formData.append('search', evt.srcElement.value);
    
    fetch(`http://localhost/projects/online-store/suggestions/${document.getElementsByName('search')[0].value}`)
        .then(response => response.json())
        .then(data => {
            if (data.length) {
                data.forEach((v, i) => {
                    let = sugestioItem = document.createElement('option');
                    sugestioItem.setAttribute('value', v.name);
                    document.querySelector("#search-sugestion").appendChild(sugestioItem);
                });
            }
        });
});

// SEARCH
const searchPost = evt => {
    evt.preventDefault();
    const searchTerms = document.getElementsByName('search')[0].value;

    if (searchTerms.length) {
        const searchUrl = evt.srcElement.getAttribute('action')
            .replace('{search}', searchTerms);
        window.location.href = searchUrl;
        return;
    }
    renderMsg('Informe os termos de busca para pesquisar', 'info');
}
formSearch.addEventListener('submit', searchPost);

// TOGGLE MENU
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
const inputList = document.querySelectorAll('.form div input:not([type="submit"], [type="reset"]), .form div textarea'),
      form = document.querySelector('form.form'),
      resetLabel = () => {
          labelList.forEach((value, index) => {
              value.classList.remove('labelIn');
              value.classList.add('labelOut');
          });
          for (let i in form) if (!isNaN(i)) form[i].style.outline = 'none';
          if (document.querySelector('p#photo-name'))
            document.querySelector('p#photo-name').innerHTML = '';
      };

if (form) {
    labelList.forEach((value, index) => {
        let currentInpt = document.querySelector(`input#${value.getAttribute('for')}`);
        if (currentInpt && currentInpt.value.length > 0) {
            value.style.top =  '-20px'; //labelIn
            value.style.font = 'size: .8em';
        }
        value.classList.remove('labelIn');
        value.classList.add('labelOut');
    });

    const inputReset = document.querySelector('input[type="reset"]');
    if (inputReset) {
        inputReset.addEventListener('click', () => {
            resetLabel();
        });
    }

    // FORM SUBMIT
    form.addEventListener('submit', evt => {
        let error = false;
        for (let i in form) {
            if (isNaN(i)) {
                break;
            } else {
                if (form[i].value.length != 0 && form[i].classList.contains('text')) {
                    let currentLabel = document.querySelector(`label[for="${form[i].getAttribute('id')}"]`);
                    if (currentLabel){
                        setTimeout(() => {
                            currentLabel.classList.replace('labelOut', 'labelIn');
                        }, 500);
                    }
                }
                let errorIcon = document.querySelector(`.form div label[for="${form[i].getAttribute('id')}"] + i`);
                if (errorIcon) {
                    errorIcon.setAttribute('class', '');
                    errorIcon.setAttribute('style', '');
                    if (form[i].value.length === 0) {
                        form[i].focus();
                        form[i].style.outline = '2px solid rgba(255, 255, 255, .5)';
                        errorIcon.setAttribute('class', 'fas fa-exclamation-circle');
                        errorIcon.setAttribute('style', 'z-index: 3; left: 102%; top: 10px; color: rgb(213, 67, 67); position: absolute;');
                        error = true;
                        break;
                    }
                }
            }
            form[i].style.outline = 'none';
        }
        if (error) {
            userMsg.querySelector('p').innerHTML = 'Preencha todos os campos';
            iconMsg.innerHTML = `<i class="fas fa-exclamation-triangle"></i>`;
            userMsg.classList.add('alert');
            userMsg.style.display = 'flex';
        }
        else {
            resetLabel();
        }
    });
}

// CLOSE MSG
const closeMsg = document.querySelector('#close-msg');
closeMsg.addEventListener('click', () => {
    userMsg.style.display = 'none';
});

const processData = (data) => {
    if (data.msg) {
        const type = (data.type ?? '' );
        renderMsg(data.msg, type);
        if (data.type == 'success' && form) {
            form.reset();
        }
    }
    if (data.redirect) {
        window.location.href = data.redirect;
    }
    return data;
}

// submit form
const submitForm = async (uri, options) => {
    userMsg.style.display = 'none';
    try {
        const response = await fetch(`http://localhost/projects/online-store${uri}`, options);
        const data = await response.json();
        return processData(data);
    } catch (err) {
        return err;
    }
}
