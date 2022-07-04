<?php $this->layout("web/__theme", ['title' => null]); ?>
<?php $this->start('styles'); ?>
    <link rel="stylesheet" href="<?= ASSETS_PATH; ?>/styles/erro.css">
<?php $this->stop(); ?>

<main>
    <h2 class="error-ops">&bull; OPS &bull;</h2>
    <p><strong>Esta página não existe.</strong> O link que você tentou acessar não foi encontrado dentro deste site.</p>
</main>
