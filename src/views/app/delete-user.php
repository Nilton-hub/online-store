<?php $this->layout('web/__theme', ['title' => $title]); ?>
<?php $this->start('styles'); ?>
<style>
    main {
        text-align: center;
    }
    /* h2 */
    .mt-5 {
        margin-top: 3rem!important;
    }
    .display-1 {
        font-size: calc(1.625rem + 4.5vw);
        font-weight: 300;
        line-height: 1.2;
        color: tomato;
    }
    /* p */
    .text-muted {
        --bs-text-opacity: 1;
        /* color: #6c757d!important; */
    }
    .lead {
        font-size: 1.25rem;
        font-weight: 300;
        font-size: 1.5em;
    }
    p {
        margin-top: 0;
        margin-bottom: 1rem;
    }

    /* a */
    .btn-group-lg>.btn, .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 1.25rem;
        border-radius: 0.3rem;
    }
    .btn {
        display: inline-block;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        text-align: center;
        text-decoration: none;
        vertical-align: middle;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        border-radius: 0.25rem;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }

    a.delete , a.cancel {
        font-size: 1.1em;
        width: 32%;
    } 
    .delete {
        background: rgb(236, 72, 72);
        color: #BDBDBD;
    }
    .cancel {
        background: lightgreen;
    }
    
    @media (min-width: 320px) { 
        main {
            width: 90% !important;
        }
    }
    @media (min-width: 768px) {
        main {
            width: 65% !important;
        }
    }
</style>
<?php $this->stop(); ?>
<main class="container text-center height-50">
    <h2 class="display-1 text-danger mt-5">Atenção</h2>
    
    <p class="text-muted lead">
        Você está prestes a deletar a sua conta. Tenha certeza que é isto que você quer. Uma vez confirmada, esta ação não poderá
        ser desfeita e todos os seus dados e publicações de produtos serão excluídos permanentemente.
    </p>
    <p>
        <a href="" alt="" title="" class="delete btn btn-danger btn-lg">Confirmar Exclusão</a> <a href="<?= $router->route('app.profile'); ?>" alt="" title="" class="cancel btn btn-success btn-lg">Cancelar</a>
    </p>
</main>

<?php $this->start('scripts'); ?>
<script>
    const del = document.querySelector('a.delete');

    const actionDelete = evt => {
        evt.preventDefault();
        const formData = new FormData();
        formData.append('delete', true);
        submitForm('/perfil/excluir', {
            method: 'POST',
            body: formData
        })
            .then(response => { console.log(response);});
    }
    del.addEventListener('click', actionDelete);
</script>
<?php $this->stop(); ?>