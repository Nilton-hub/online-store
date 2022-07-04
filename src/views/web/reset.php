<?php $this->layout('web/__theme', ['title' => $title]); ?>

<main class="main-content" style="margin: 0 auto 120px auto;">
    <p class="container">Informe e repita sua senha e clique em <b>Alterar</b>.</p>

    <form class="form form-reset" method="POST" action="<?= $router->route('web.resetPost'); ?>">
    <input type="hidden" name="token" value="<?= $code; ?>">
        <div>
            <input type="password" name="password" class="text" id="input-password">
            <i class="fas fa-envelope-square icon-simbol-left"></i>
            <label for="input-password">Senha:</label>
            <i class="errorIcon"></i>
        </div>
        <div>
            <input type="password" name="password_re" class="text" value="" id="input-password_re">
            <i class="fas fa-envelope-square icon-simbol-left"></i>
            <label for="input-password_re">Repita a sua senha:</label>
            <i class="errorIcon"></i>
        </div>
        <p>Lembrou a senha? <a href="<?= $router->route('web.login'); ?>">Voltar e logar.</a>
        <div class="flex">
            <input type="submit" value="Alterar">
            <input type="reset" value="Limpar os Campos">
        </div>
    </form>
</main>

<?php $this->start('scripts'); ?>
    <script>
        const sendData = evt => {
            // /recuperar/resetar
            evt.preventDefault();
            formData = new FormData(form);
            formData.append('password_reset', true);
            submitForm(`/recuperar/resetar`, {
                method: 'POST',
                body: formData
            });
        }
        form.addEventListener('submit', sendData);
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
                            case 'input-password':
                                value.setAttribute('placeholder', `Crie uma senha`);
                            break;
                            case 'input-password_re':
                                value.setAttribute('placeholder', `Repita a senha`);
                            break;
                        default:
                            break;
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
        // fetch('<?= $router->route('web.resetPost'); ?>', {
        //         method: 'POST',
        //         body: formData
        //     })
        //         .then(response => { console.log(response.text()) });
        
    </script>
<?php $this->stop(); ?>
