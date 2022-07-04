<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    
    <link rel="icon" type="image/png" sizes="16x16" href="<?= url('/storage/images/favicons'); ?>/favicon-16x16.png">
    <link rel="stylesheet" href="<?= ASSETS_PATH; ?>/fontawesome-free/css/all.css">
    <link rel="stylesheet" href="<?= ASSETS_PATH; ?>/styles/style.css">
    <link rel="stylesheet" href="<?= ASSETS_PATH; ?>/styles/media-queries.css">
    <?php if ($this->section('styles')): ?>
        <?= $this->section('styles'); ?>
    <?php endif; ?>
    <title>Loja de Produtos Online | <?= ($title ?? null); ?></title>
</head>
<body>
<header class="main-header">
    <form action="<?= $router->route('web.search'); ?>" method="GET" class="form-search">
        <input type="search" name="search" placeholder="Encontre um produto..." list="search-sugestion">
        <button><i class="fas fa-search"></i></button>
        <datalist id="search-sugestion"></datalist>
    </form>
    <!-- LOGO -->
    <img src="<?= ASSETS_PATH; ?>/images/logo.svg" alt="Logo Online Store" class="logo" onclick="redirectHome();">
    <div class="authUser" style="">
        <?php if (!empty($_SESSION['authToken']) || !empty($_COOKIE['authToken'])): ?>
            <a href="<?= $router->route('app.logout'); ?>">Sair <i class="fas fa-power-off"></i></a>
        <?php else: ?>
            <a href="<?= $router->route('web.login'); ?>">Entrar <i class="fas fa-sign-in-alt"></i></a> 
        <?php endif; ?>
    </div>
    <nav class="nav">
        <button class="btn-menu" aria-expanded="false" aria-controls="main-menu" aria-label="Abrir menu" aria-haspopup="true"><span id="menu-hamburger"></span></button>
        <ul id="main-menu" role="menu">
            <li>
                <?php if (!empty($_SESSION['authToken']) || !empty($_COOKIE['authToken'])): ?>
                    <a href="<?= $router->route('app.profile'); ?>"><i class="fas fa-user"></i> Perfil</i></a>
                    <div class="underline-hover"></div>
                <?php else: ?>
                    <a href="<?= $router->route('web.register'); ?>">Registre-se</i></a>
                    <div class="underline-hover"></div>
                <?php endif; ?>
            </li>
            <li>
                <a href="<?= $router->route('app.createProduct'); ?>">Publicar Produto</a>
                <div class="underline-hover"></div>
            </li>
            <li>
                <a href="<?= $router->route('web.home'); ?>">Home</a>
                <div class="underline-hover"></div>
            </li>
        </ul>
    </nav>
</header>
<h1 class="container"><?=$this->e($title);?></h1>

<?php $flash = message_flash(); ?>
<?php if(!empty($flash['msg'])): ?>
    <div class="message bounce <?= $flash['type']; ?>">
        <span id="msg-icon"><i class="fas fa-check-circle"></i></span>
        <p><?= $flash['msg']; ?></p>
        <span id="close-msg">
            <i class="far fa-times-circle"></i>
        </span>
    </div>
<?php else: ?>
    <div class="message bounce" style="display: none;">
        <span id="msg-icon"></span><p></p>
        <span id="close-msg">
            <i class="far fa-times-circle"></i>
        </span>
    </div>
<?php endif; ?>

<?=$this->section('content')?>

<div id="load-box-animate" class="none"></div>

<footer class="main-footer">
    <div class="footer-content">
        <p class="copy">&copy; 2021-2022 - Criado e Editado por Nilton Duarte</p>
        <ul class="contact-list">
            <li><a href="https://www.facebook.com/nilton.duartte100/" alt="" title="" target="_blank">
                <i class="fab fa-facebook" style="font-size: 1.5em; display: block; text-align: center; margin: auto;"></i>
                <p>Página do Facebook</p>
            </a></li>
            <li><a href="https://instagram.com/n.i.l.t.o.n.1" alt="" title="" target="_blank">
                <i class="fab fa-instagram-square" style="font-size: 1.5em; display: block; text-align: center; margin: auto;"></i>
                <p>@n.i.l.t.o.n.1</p>
            </a></li>
            <li><a href="mailto:tvirapegubeco@gmail.com" alt="" title="" target="_blank">
                <i class="fas fa-envelope-square" style="font-size: 1.5em; display: block; text-align: center; margin: auto;"></i>
                <p>tvirapegubeco@gmail.com</p>
            </a></li>
            <li><a href="https://wa.me/5586999878575" alt="" title="" target="_blank">
                <i class="fab fa-whatsapp-square" style="font-size: 1.5em; display: block; text-align: center; margin: auto;"></i>
                <p>(86) 9 99878575</p>
            </a></li>
        </ul>
    </div>
</footer>
<script src="<?= ASSETS_PATH; ?>/icons/fontawesome-free-5.15.4-web/js/all.js"></script>
<script src="<?= ASSETS_PATH; ?>/scripts/script.js"></script>
<?php if ($this->section('scripts')): ?>
    <?= $this->section('scripts'); ?>
<?php endif; ?>
</body>
</html>
