<div class="product">
    <h3 class="product__title"><?= $product->name; ?></h3>
    <div class="product__cover">
        <img src="<?= url("/{$product->cover}"); ?>" alt="">
    </div>
    <div class="product__details">
        <h3><?= $product->name; ?></h3>
        <span class="product__price">R$ <?= number_format($product->price, 2, ',', '.'); ?></span>
        <p>
        <?= $product->description; ?>
        </p>
        <div class="product_owner">
            <div><strong>Por: </strong><u><?= $product->user()->name; ?></u></div>
            <div><strong>Contato: </strong><?= phone($product->user()->phone) . " / {$product->user()->email}"; ?></div>
        </div>
    </div>
</div>