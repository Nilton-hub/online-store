<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('/src/views/shared/style.css'); ?>">
    <title>Recuperar Senha</title>
    <style>
        body {
            background: #33333d;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Roboto Condensed', sans-serif;
            color: #bdbdbd;
            font-size: 18px;
        }
        header {
            text-align: center;
            background: rgba(0, 0, 0, .5);
            padding: 20px;
        }
        main {
            width: 80%;
            margin: 55px auto;
            text-align: center;
        }
        h1 {
            color: #f8f8f9;
            padding: 0;
            margin: 0;
        }
        a {
            color: #5b5bff;
            text-transform: uppercase;
        }
        .sep::before, .sep::after {
            content: '';
            display: inline-block;
            width: 80px;
            height: 2px;
            background-color: #bdbdbd;
            margin: 0 10px;
            margin-bottom: 3px;
        }
        footer {
            border-top: 1px solid rgba(248, 248, 249, 0.4);
            padding-top: 20px;
            font-size: 1.5em;
        }
        footer .contact {
            display: flex;
            justify-content: space-around;
        }
        footer a {
            text-decoration: none;
        }

        footer .logo {
            display: block;
            transform: scale(3) translateY(3px);
        }
    </style>
</head>
<body>
    <header>
        <h1>Online Store</h1>
    </header>
    <main>
        <p>Olá, <?= $username; ?>!</p>
        <p>
            Você está recebendo este email, pois foi solicitado a recuperação de senha em nosso site.
        </p>
        <p>
            <strong style="text-transform: uppercase;">Importante:</strong> Se você não solicitou, apenas ignore-o. Seus dados permanecem seguros.
        </p>
        <div class="sep">ou</div>
        <p><a href="<?= $usernamelink; ?>">Cique aqui para recuperar</a></p>
        <p><?= $usernamelink; ?></p>
    </main>
    <footer>
        <div class="contact">
            <a href="https://www.facebook.com/nilton.duartte100/" target="_blank" title="Link do  Facebook"><span class="icon-facebook"></span></a>
            <a href="<?= url(); ?>" target="_blank" title="Link do  Site">
                <img src="<?= url('/src/views/assets/images/logo.svg'); ?>" alt="Logo Online Store" class="logo" width="20">
            </a>
            <a href="https://www.instagram.com/n.i.l.t.o.n.1/" target="_blank" title="Link do  Instagram"><span class="icon-instagram"></span></a>
        </div>
    </footer>
</body>
</html>
