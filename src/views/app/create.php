<?php $this->layout('web/__theme', [
    'title' => 'Publicar'
    ]); ?>

<main class="main-content">
    <p class="container">
        Preencha os campos abaixo e clique em <b>publicar</b> para publicar um novo produto no registro.
    </p>
    <form class="form" method="post" action="javascript:void(0)">
        <div>
            <input type="text" name="name" id="input-name" maxlength="30">
            <label for="input-name">Produto:</label>
            <i class="errorIcon"></i>
        </div>
        <div class="textarea">
            <textarea name="desc" id="text-desc"></textarea>
            <label for="text-desc">Descrição:</label>
            <i class="errorIcon"></i>
        </div>
        <div style="margin: 5px 0;">
            <input type="text" name="price" id="input-price">
            <label for="input-price">Preço R$:</label>
            <i class="errorIcon"></i>
        </div>
        <span>Reais e centavos separados por virgula. Ex.: 25,30</span>
        <p id="photo-name"></p>
        <div class="file">
            <label for="input-cover">Foto do seu produto: <i class="fas fa-camera"></i></label>
            <input type="file" name="cover" id="input-cover">
            <i class="errorIcon"></i>
        </div>
        <div class="category" id="category">
            <label for="category">Categoria</label>
            <select name="category" id="category">
                <option value="0" selected disabled>-- Selecione:</option>
                <?php foreach($categories as $category): ?>
                    <option value="<?= $category->id; ?>"><?= mb_convert_case($category->name, MB_CASE_TITLE) ?></option>
                <?php endforeach; ?>
            </select>
            <i class="errorIcon"></i>
        </div>
        <div class="flex">
            <input type="submit" value="Publicar">
            <input type="reset" value="Limpar os Campos">
        </div>
    </form>
</main>

<?php $this->start('scripts'); ?>
<script src="<?= ASSETS_PATH; ?>/scripts/create.js"></script>
<?php $this->stop(); ?>
