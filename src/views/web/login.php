<?php $this->layout('web/__theme', ['title' => $title]); ?>

<?php $this->start('styles'); ?>
    <link rel="stylesheet" href="<?= ASSETS_PATH; ?>/styles/login.css">
<?php $this->stop(); ?>

<main class="main-content">
    <p class="container">Informe os seus dados e clique em entrar para acessar a sua conta.</p>
    <form class="form form-login" method="post" action="javascript:void(0)">
        <div>
            <input type="email" name="email" class="text" id="input-email">
            <i class="fas fa-envelope-square icon-simbol-left"></i>
            <label for="input-email">Seu email:</label>
            <i class="errorIcon"></i>
        </div>
        <div>
            <input type="password" name="password" class="text" id="input-password">
            <i class="fas fa-lock icon-simbol-left"></i>
            <label for="input-password">Sua Senha:</label>
            <span class="icon-simbol-right"><i class="fas fa-eye-slash" id="iconEye"></i></span>
            <i class="errorIcon"></i>
        </div>
        <div class="remember">
            <label for="input-remember">
                <input type="checkbox" name="remember" id="input-remember">
                <span id="check-animated"></span>
                Lembrar dados de login:
            </label>
            <i class="errorIcon"></i>
        </div>
        <p class="form__auth"><span>Ainda não tem conta?&nbsp; <a href="<?= $router->route('web.register'); ?>">Cadastre-se de graça.</a></span>
        &nbsp;| &nbsp;<a href="<?= $router->route('web.forget'); ?>" title="Recuperar senha" alt="Recuperar senha">Esqueci a senha</a></p>
        <div class="flex">
            <input type="submit" value="Entrar">
            <input type="reset" value="Limpar os Campos" style="padding: 0;">
        </div>
    </form>
</main>

<?php $this->start('scripts'); ?>
    <script src="<?= ASSETS_PATH; ?>/scripts/login.js"></script>
<?php $this->stop(); ?>
