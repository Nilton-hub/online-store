const opt = document.querySelectorAll('.options'),
  optList = document.querySelectorAll('.options ul'),
  modalContainer = document.querySelector('#modal'),
  confirm = document.querySelector('.modal.confirm'),
  formProfile = document.querySelector('#user-edit'),
  formPassword = document.querySelector('#update-password'),
  btnProfile = document.querySelector('#btn-edit'),
  formProduct = document.querySelector('#product-edit'),
  editItems = document.querySelectorAll('li[class^="pdt-options"]'),
  inputsCovers = document.querySelectorAll('input[id^="cover-"]'),
  profileOptins = document.querySelector('.change-password'),
  moreProfileOptins = document.querySelector('.user-profile .more');

let editCancel;

const toggleOptions = id => {
    const elem = document.getElementById(id);
    if (elem) elem.classList.toggle('none');
}

const windowClick = evt => {
    if (!evt.target.classList.contains('pdt-options')) {
        optList.forEach((value, index) => {
            if (!value.classList.contains('none') && !(['svg', 'path'].indexOf(evt.target.tagName) != -1)) {
                value.classList.add('none');
            }
        });
    }
}

// TOGGLE PROFILE OPTIONS
const toggleProfileOptions = () => {
    profileOptins.classList.toggle('none');
    document.querySelector('.profile-chevron-right')
        .classList.toggle('rotate90');
}
moreProfileOptins.addEventListener('click', toggleProfileOptions);

const updatePassword = evt => {
    evt.preventDefault();
    const formData = new FormData(formPassword);
    submitForm('/perfil/senha', {
        method: 'POST',
        body: formData
    });
}
formPassword.addEventListener('submit', updatePassword)

// EDIT PRODUCT
const productEdit = (id) => {
    editCancel = () => {
        formProduct.removeEventListener('submit', send);
        formProduct.reset();
    }
    document.querySelector('#btn-close-modal').addEventListener('click', editCancel);
    
    const send = () => {
        loadBox.innerHTML = '<div id="load-icon-spinner"></div><p>Aguarde, carregando...</p>';
        loadBox.classList.remove('none');
        const formData = new FormData(formProduct);
        formData.append('id', id);

        const data = submitForm('/produto/editar', {
            method: 'POST',
            body: formData
        });
        data.then(response => {
            loadBox.classList.add('none');
            loadBox.innerHTML = '';
            if (response.data) {
                const datPdt = response.data;
                document.getElementById(`title-prod-v-mobile-${datPdt.id}`).innerHTML = datPdt.name;
                document.getElementById(`title-prod-v-desktop-${datPdt.id}`).innerHTML = datPdt.name;
                document.getElementById(`pdt-price-${datPdt.id}`).innerHTML = formater.format(datPdt.price);
                document.getElementById(`cover-prod-${datPdt.id}`).setAttribute('src', datPdt.cover);
                document.getElementById(`desc-prod-${datPdt.id}`).innerHTML = datPdt.description;
             }
        });
    }
    formProduct.addEventListener('submit', send);
}

const actionEdit = evt => {
    const id = evt.srcElement.getAttribute('data-id');
    const categoryId = document.querySelector(`#pdt-item-${id} [id^="category-"]`);
    
    setTimeout(() => {
        for (field in formProduct) {
            if (!isNaN(field) && formProduct[field].classList.contains('text')) {
                let fieldId = formProduct[field].getAttribute('id');
                formProduct.querySelector(`label[for="${fieldId}"]`).classList.replace('labelOut', 'labelIn');
            }
        }
    }, 500);

    formProduct.querySelector('[name="name"]').value = document.querySelector(`h3#title-prod-v-desktop-${id}`).innerText;
    formProduct.querySelector('[name="desc"]').value = document.querySelector(`p#desc-prod-${id}`).innerText;
    formProduct.querySelector('[name="price"]').value = document.querySelector(`span#pdt-price-${id}`).innerText.replace('R$ ', '');
    formProduct.querySelector(`[name="category"] option[value="${categoryId.getAttribute('data-category-id')}"]`)
        .setAttribute('selected', '');
    formProduct.classList.remove('none');
    productEdit(id);
}

// DELETE PRODUCT
const productDelete = id => {
    const btnOk = document.querySelector('#btn-delete-post');
    const btnCancel = document.querySelector('#btn-cancel-delete');
    const btnCloseModal = document.querySelector('#btn-close-modal');

    const send = () => {
        const formData = new FormData();
        formData.append('id', id);
        submitForm('/produto/deletar', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                const removed = document.querySelector(`#pdt-item-${id}`);
                if (removed) {
                    removed.classList.add('removed');
                    setTimeout(() => {
                        removed.remove();
                    }, 360);
                }
            })
            .catch(error => console.log(error));
    }

    const cancel = () => {
        btnOk.removeEventListener('click', send);
    }
    btnOk.addEventListener('click', send);

    btnCancel.addEventListener('click', cancel);
    btnCloseModal.addEventListener('click', cancel);
}

const actionDelete = (evt) => {
    confirm.classList.remove('none');
    confirm.style.display = 'block';
    const id = evt.srcElement.getAttribute('data-id');
    productDelete(id);
}

// edit & delete
editItems.forEach((value, index) => {
    if (value.classList.contains('pdt-options-edit')) {
        value.addEventListener('click', actionEdit);
    } else if (value.classList.contains('pdt-options-delete')) {
        value.addEventListener('click', actionDelete);
    }
});

// OPEN MENU OPTIONS
window.addEventListener('click', windowClick);

const backgroundModal = evt => {
    modalContainer.classList.add('modal-background');
    modalContainer.classList.remove('none');
}
editItems.forEach((value, index) => {
    value.addEventListener('click', backgroundModal);
});

// EDIT PROFILE
const editProfile = evt => {
    evt.preventDefault();
    modalContainer.classList.add('modal-background');
    modalContainer.classList.remove('none');
    formProfile.classList.remove('none');
    const labels = formProfile.querySelectorAll('label');
    console.log(formProfile);
    setTimeout(() => {
        labels.forEach(element => {
            element.classList.replace('labelOut', 'labelIn');
        });
    }, 400);
}

btnProfile.addEventListener('click', editProfile);

// CLOSE MODAL
function closeModal() {
    modalContainer.classList.remove('modal-background');
    modalContainer.classList.add('none');
    if (!formProduct.classList.contains('none')) {
        formProduct.classList.add('none');
    }
    if (!formProfile.classList.contains('none')) {
        formProfile.classList.add('none');
    }
    confirm.style.display = 'none'
}

/* ----------------------------- FORM ------------------------------------ */
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
            case 'input-fone':
                value.setAttribute('placeholder', `(00) 9 0000-0000`);
                break;
        default:
            break
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

xhr = new XMLHttpRequest();
formProfile.addEventListener('submit', evt => {
    const formData = new FormData(form);
    xhr.open('POST', 'http://localhost/projects/online-store/perfil/editar');
    xhr.send(formData);
    xhr.onreadystatechange = () => {
        if (xhr.status >= 200 && xhr.status < 300 && xhr.readyState == 4) {
            if (xhr.responseText) {
                const data = JSON.parse(xhr.responseText);
                processData(data);
                document.querySelector('#user-name').innerHTML = data.data.name + ';';
                document.querySelector('#user-email').innerHTML = data.data.email + ';';
                document.querySelector('#user-phone').innerHTML = data.data.phone + '.';
            }
        }
    }
});

// SUBMIT COVER
inputsCovers.forEach((element, index) => {
    const sendCover = evt => {
        const formData = new FormData(),
              id = evt.srcElement.getAttribute('data-id'),
              spanProgress = document.querySelector(`#pct-progress-covre-${id}`)
              progressBar = document.querySelector(`#progress-bar-${id}`);
        formData.append('cover', evt.target.files[0]);
        formData.append('id', id);

        const showProgress = evt => {
            let pct = Math.floor(evt.loaded * 100 / evt.total);
            spanProgress.classList.remove('none');
            progressBar.classList.remove('none');
            spanProgress.innerHTML = `Enviando, ${pct}% concluído...`;
            progressBar.value = pct;
        }
        xhr.upload.addEventListener('progress', showProgress);
        xhr.open('POST', 'http://localhost/projects/online-store/produto/atualizar-capa-produto');
        xhr.send(formData);
        xhr.onreadystatechange = () => {
            if (xhr.status >= 200 && xhr.status < 300 && xhr.readyState == 4) {
                setTimeout(() => {
                    spanProgress.classList.add('none');
                    progressBar.classList.add('none');
                    spanProgress.innerHTML = ``;
                    progressBar.value = '';
                }, 4000);

                const response = JSON.parse(xhr.responseText);
                if (response.data && response.data.cover) {
                    document.getElementById(`cover-prod-${id}`).setAttribute('src', response.data.cover);
                }
                processData(response);
                element.value = '';
            }
        }
    }
    element.addEventListener('change', sendCover);
});
