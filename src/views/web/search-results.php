<?php $this->layout('web/__theme', ['title' => $title]); ?>

<?php $this->start('scripts'); ?>
<link rel="stylesheet" href="<?= ASSETS_PATH; ?>/styles/product.css">
<link rel="stylesheet" href="<?= ASSETS_PATH; ?>/styles/search.css">
<?php $this->stop(); ?>

<main>
    <div class="container search-header">
        <h2 style="">Seus resultados para "<i><?= $terms; ?></i>"</h2>
        <p>Total de <u><?= $total; ?></u> resultado(s).</p>
    </div>
    <div class="border"></div>
    <div class="container">
        <?php if ($total > 0): ?>
            <?php foreach($results as $result): ?>
            <?php $this->insert('partials/product', [
                'product' => $result
            ]); ?>
        <?php endforeach; ?>
        <?php else: ?>
            <div>
                <h3>Sua pesquisa não retornou resultados. Você pode tentar outros termos.</h3>
                <img src="<?= url("/storage/images/magnifying-glass-search.png"); ?>" class="no-results-img"
                style="">
            <div>
        <?php endif; ?>
    </div>
</main>

<?= $pager; ?>
