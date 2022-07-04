<?php $this->layout('web/__theme', ['title' => 'Editar Prodto']); ?>

<?php $this->start('styles'); ?>
    <link rel="stylesheet" href="<?= ASSETS_PATH; ?>/styles/product.css">
    <link rel="stylesheet" href="<?= ASSETS_PATH; ?>/styles/profile.css">
<?php $this->stop(); ?>

<main>
    <section class="user-profile container">
        <div class="user-profile__title">
            <h2>Informações Pessoais</h2>
            <a href="" id="btn-edit">Editar</a>
        </div>
        <div class="user-profile__data">
            <div><b>Nome:</b> <span id="user-name"><?= $user->name; ?>;</span></div>
            <div><b>Email:</b> <span id="user-email"><?= $user->email; ?>;</span></div>
            <div><b>Telefone:</b> <span id="user-phone"><?= phone($user->phone); ?>.</span></div>
        </div>
        <div class="more">
            <span>Mais</span>
            <span class="profile-chevron-right" style="transition: .2s;">
                <i class="fas fa-chevron-right"></i>
            </span>
        </div>
        <div class="change-password none">
            <form action="<?= $router->route('app.updatePassword'); ?>" method="POST" id="update-password">
                <h3>Alterar Senha</h3>
                <label for="">
                    <div>Senha Atual:</div>
                    <input type="password" name="passwd_actual" id="passwd_actual">
                </label>
                <label for="">
                    <div>Nova Senha:</div>
                    <input type="password" name="passwd" id="passwd">
                </label>
                <label for="">
                    <div>Repita a Senha criada:</div>
                    <input type="password" name="passwd_re" id="passwd_re">
                </label>
                <button type="submit">Alterar</button>
            </form>
            <p><a href="<?= $router->route('app.deleteUser'); ?>" alt="" title="">Excluir minha conta</a></p>
        </div>
    </section>

    <section class="posts">
        <h2 class="container">Suas Publicações</h2>
        <?php if (!empty($products)): ?>
            <?php foreach($products as $product): ?>
                <article>
                    <div class="product" id="pdt-item-<?= $product->id; ?>">
                        <div>
                            <div class="options" onclick="toggleOptions('options-<?= $product->id; ?>');">
                                <div id="pdt-options-<?= $product->id; ?>" class="pdt-options"><i class="fas fa-ellipsis-v pdt-options"></i></div>
                                <ul class="none" id="options-<?= $product->id; ?>">
                                    <!-- Editar -->
                                    <li class="pdt-options-edit" data-id="<?= $product->id; ?>"><i class="fas fa-edit"></i> Editar</li>
                                    <!-- Deletar -->
                                    <li class="pdt-options-delete" data-id="<?= $product->id; ?>"><i class="fas fa-trash"></i> Deletar</li>
                                </ul>
                            </div>
                            <h3 class="product__title" id="title-prod-v-mobile-<?= $product->id; ?>"><?= $product->name; ?></h3>
                        </div>

                        <div class="product__cover"
                        style="position: relative;">
                            <form action="http://localhost/projects/online-store/produto/editar" id="form-cover-update-<?= $product->id; ?>" method="POST" enctype="multipart/form-data">
                                <img src="<?= url("/{$product->cover}"); ?>" id="cover-prod-<?= $product->id; ?>" alt="Foto de capa: <?= $product->name; ?>" title="Foto de capa: <?= $product->name; ?>">
                                <label for="cover-<?= $product->id; ?>" 
                                style="position: absolute; bottom: 5px; right: 5px; background: rgba(112, 110, 110, .6); padding: 10px; border-radius: 3px; color: white; cursor: pointer;">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" id="cover-<?= $product->id; ?>" data-id="<?= $product->id; ?>" style="display: none;">
                                <div style="margin: 8px;">
                                    <span id="pct-progress-covre-<?= $product->id; ?>" class="none"></span>
                                    <progress id="progress-bar-<?= $product->id; ?>" class="none"></progress>
                                </div>
                            </form>
                        </div>
                        <div class="product__details">
                            <h3  id="title-prod-v-desktop-<?= $product->id; ?>"><?= $product->name; ?></h3>
                            <span class="product__price" id="pdt-price-<?= $product->id; ?>">R$ <?= number_format($product->price, 2, ',', '.'); ?></span>
                            <p id="desc-prod-<?= $product->id; ?>">
                            <?= $product->description; ?>
                            </p>
                            <div>
                                <strong>Categoria: </strong>
                                <span id="category-<?= $product->category()->id; ?>" data-category-id="<?= $product->category()->id; ?>">
                                    <?= ($product->category()->id != 6 ? $product->category()->name : 'Não especificado'); ?>
                                </span>
                            </div>
                            <div class="product_owner" style="margin-top: 20px;">
                                <div><strong>Por: </strong><u><?= $product->user()->name; ?></u></div>
                                <div><strong>Contato: </strong> <?= phone($product->user()->phone) . " / {$product->user()->email}"; ?></div>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-feed">
                <img src="<?= url("/storage/images/OAIEOU0.jpg"); ?>" style="border-radius: 50%;" alt="">
                <p class="container" style="text-align: center;">Você ainda não publicou nenhum produto para anuncio</p>
            </div>
        <?php endif; ?>
    </section>

    <!-- MODAL -->
    <div class="none" id="modal">
        <div class="icon" onclick="closeModal()" id="btn-close-modal"><i class="far fa-times-circle"></i></div>
        <form class="form none modal" method="post" id="user-edit" action="javascript:void(0)" style="z-index: 15;">
            <div>
                <input type="text" name="name" value="<?= $user->name; ?>" id="input-user-name">
                <label for="input-user-name">Seu Nome:</label>
                <i class="errorIcon"></i>
            </div>
            <div>
                <input type="email" name="email" value="<?= $user->email; ?>" id="input-email">
                <label for="input-email">Seu email:</label>
                <i class="errorIcon"></i>
            </div>
            <div>
                <input type="text" name="phone"  value="<?= $user->phone; ?>" id="input-fone">
                <label for="input-fone">Telefone:</label>
                <i class="errorIcon"></i>
            </div>
            <span id="msgPassword" style=""></span>
            <div class="flex">
                <input type="submit" value="Salvar">
                <input type="reset" value="Limpar os Campos">
            </div>
        </form>

        <form class="form none modal" method="post" id="product-edit" action="javascript:void(0)">
            <div>
                <input type="text" name="name" id="input-product-name" class="text">
                <label for="input-product-name">Produto:</label>
                <i class="errorIcon"></i>
            </div>
            <div class="textarea" style="margin-bottom: 25px;">
                <textarea name="desc" id="text-desc" class="text"></textarea>
                <label for="text-desc">Descrição:</label>
                <i class="errorIcon"></i>
            </div>
            <div style="margin: 5px 0;">
                <input type="text" name="price" id="input-price" class="text">
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
                    <?php if ($categories): ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category->id; ?>"><?= $category->name; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <i class="errorIcon"></i>
            </div>
            <div class="flex">
                <input type="submit" value="Salvar">
                <input type="reset" value="Limpar os Campos">
            </div>
        </form>

        <div class="confirm none modal" style="display: none;" >
            <p>
                Tem certeza que deseja exclir permanentemente esta publicação?<br>
                A ação não podera ser desfeita.
            </p>
            <div>
                <button id="btn-delete-post">Deletar</button> <button id="btn-cancel-delete" onclick="closeModal()">Manter</button>
            </div>
        </div>
    </div>
    <!-- END MODAL -->
</main>

<?php $this->start('scripts'); ?>
    <script src="<?= ASSETS_PATH; ?>/scripts/profile.js"></script>
    <script src="<?= ASSETS_PATH; ?>/scripts/imask.js"></script>
    <script>
        IMask(
            document.querySelector('#input-fone'),
            {mask: '(00) 9 0000-0000'}
        );
    </script>
<?php $this->stop(); ?>
