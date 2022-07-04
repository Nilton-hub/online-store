const opt = document.querySelectorAll('.options');
const optList = document.querySelectorAll('.options ul');

const toggleOptions = evt => {
    optList.classList.toggle('none');
}

opt.forEach((value, index) => {
    value.addEventListener('click', toggleOptions, true);
});

// Close options product edit
const windowClick = evt => {
    if (!evt.target.classList.contains('pdt-options')) {
        if (!optList.classList.contains('none') && !(['svg', 'path'].indexOf(evt.target.tagName) != -1)) {
            optList.classList.add('none'); 
        }
    }
}

// Open menu options
window.addEventListener('click', windowClick);


const deletePost = evt => {
    modalContainer.classList.add('modal-background');
    modalContainer.classList.remove('none');
    confirm.style.display = 'block'
}

editItems.forEach((value, index) => {
    value.addEventListener('click', deletePost);
});
