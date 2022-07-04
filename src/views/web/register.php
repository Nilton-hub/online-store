<?php $this->layout('web/__theme', ['title' => 'Criar Conta']); ?>

<main>
    <p class="container">
        Preencha os campos abaixo e clique em <b>cadastrar</b> para criar a sua conta.
    </p>
    <form class="form" method="post" action="javascript:void(0)">
        <div>
            <input type="text" name="name" class="text" id="input-name">
            <label for="input-name">Seu Nome:</label>
            <i class="errorIcon"></i>
        </div>
        <div>
            <input type="email" name="email" class="text" id="input-email">
            <label for="input-email">Seu email:</label>
            <i class="errorIcon"></i>
        </div>
        <div>
            <input type="text" name="phone" class="text" id="input-fone">
            <label for="input-fone">Telefone:</label>
            <i class="errorIcon"></i>
        </div>
        <div>
            <input type="password" name="password" class="text" id="input-password">
            <label for="input-password">Crie uma Senha:</label>
            <i class="errorIcon"></i>
        </div>
        <div>
            <input type="password" name="password_re" class="text" id="input-password_re">
            <label for="input-password_re">Repita a senha:</label>
            <i class="errorIcon"></i>
        </div>
        <span id="msgPassword" style=""></span>
        <p>Já tem conta? <a href="<?= $router->route('web.login'); ?>">Faça login.</a></p>
        <div class="flex">
            <input type="submit" value="Cadastrar">
            <input type="reset" value="Limpar os Campos">
        </div>
    </form>
</main>
<?php $this->start("scripts"); ?>
    <script src="<?= ASSETS_PATH; ?>/scripts/register.js"></script>
    <script src="<?= ASSETS_PATH; ?>/scripts/imask.js"></script>
    <script !src="">
        IMask(
            document.querySelector('#input-fone'),
            {
                mask: '(00) 9 0000-0000'
            }
        );
    </script>
<?php $this->stop(); ?>
