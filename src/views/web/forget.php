<?php $this->layout('web/__theme', ['title' => $title]); ?>

<?php $this->start('styles'); ?>
    <style>
        .main-content {
            width: 80%;
            margin: 0 auto;
        }
    </style>
<?php $this->stop(); ?>

<main class="main-content" style="">
    <p class="container">Informe abaixo o email que você usa para acessar sua conta e clique em <b>Recuperar</b>. Enviaremos um link de recuperação para você criar uma nova senha.</p>
    <form class="form form-forget" method="post" action="javascript:void(0)">
        <div>
            <input type="email" name="email" class="text" id="input-email">
            <i class="fas fa-envelope-square icon-simbol-left"></i>
            <label for="input-email">Seu email:</label>
            <i class="errorIcon"></i>
        </div>

        <p>Lembrou a senha? <a href="<?= $router->route('web.login'); ?>">Voltar e logar.</a>
        <div class="flex">
            <input type="submit" value="Recuperar">
            <input type="reset" value="Limpar os Campos">
        </div>
    </form>
</main>

<?php $this->start('scripts'); ?>
    <script>
        if (inputList) {
            let inputEmail = document.querySelector(`#input-email`);

            const sendData = () => {
                submitForm('/recuperar', {
                    method: 'POST',
                    body: new FormData(form)
                })
                        // .then(response => { console.log(response); });
            }
            form.addEventListener('submit', sendData);
            
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
    </script>
<?php $this->stop(); ?>
