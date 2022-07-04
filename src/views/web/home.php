<?php $this->layout('web/__theme', ['title' => $title]); ?>

<?php $this->start('styles'); ?>
    <link rel="stylesheet" href="<?= ASSETS_PATH; ?>/styles/product.css">
    <style>
        @keyframes blink-bar {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        p#desc::after {
            content: '|';
            margin-left: 2px;
            opacity: 1;
            animation: blink-bar .7s infinite;
        }
        
        p#desc {
            font-size: 1.1em;
            font-family: "Noto Serif";
            text-align: left;
            width: 80%;
            margin: 10px auto;
            letter-spacing: .3px;
            height: 2.2em;
        }
    </style>
<?php $this->stop(); ?>

<main>
    <p class="container" id="desc">
        Publique em nosso feed um produto que você desja vender ou encontre algo publicado por outros usuários.
    </p>
    <?php if (!empty($total)): ?>
        <?php foreach($products as $product): ?>
            <?php $this->insert('partials/product', ['product' => $product]); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="container empty-feed">
            <h2>Nada Por Aqui!</h2>
            <img style="" src="<?= url("/storage/images/OAIEOU0.jpg"); ?>" alt="">
            <p>
                Quando os usuários cadastrarem seus produtos, eles aparecerão aqui.
            </p>
        </div>
    <?php endif; ?>
    <?= $pager; ?>
</main>
<?php $this->start('scripts'); ?>
<script>
    const description = document.querySelector('p#desc');

    const writeMachine = (element) => {
        const letterList = element.innerHTML.split('');
        element.innerHTML = '';
        letterList.forEach((val, index) => {
            setTimeout(() => element.innerHTML += val, 75 * index);
        });
    }

    writeMachine(description);
    description.innerHTML = '';
    setInterval(() => {
        writeMachine(description);
        description.innerHTML = 'Publique em nosso feed um produto que você desja vender ou encontre algo publicado por outros usuários.';
        description.innerHTML = '';
    }, 16000);
</script>
<?php $this->stop(); ?>
