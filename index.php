<?php
require __DIR__ . '/vendor/autoload.php';
session_start();
ob_start();

use \CoffeeCode\Router\Router;
//public
$router = new Router(URL_BASE);

$router->namespace('\src\controllers')->group(null);
$router->get("/", "Web:home", "web.home");
$router->get("/{page}", "Web:home", "web.home");
$router->get("/suggestions/{search}", "Web:searchSuggestions", "web.searchSuggestions");
$router->get("/encontrar/{search}", "Web:searchPost", "web.search");
$router->get("/encontrar/{search}/{page}", "Web:searchPost", "web.search");

//auth
$router->get("/registrar", "Web:register", "web.register");
$router->post("/registrar", "Web:register", "web.register");
$router->get("/entrar", "Web:login", "web.login");
$router->post("/entrar", "Web:login", "web.loginPost");
$router->get("/sair", "App:logout", "app.logout");

//reset
$router->group('/recuperar');
$router->get("/", "Web:forget", "web.forget");
$router->post("/", "Web:forget", "web.forget");
$router->get("/{token}", "Web:reset", "web.reset");
$router->post("/resetar", "Web:reset", "web.resetPost");

//restrict
$router->group('/perfil');
$router->get("/", "App:profile", "app.profile");
$router->post('/editar', 'App:editUser', 'app.editUser');
$router->post('/excluir', 'App:deleteUser', 'app.deleteUser');
$router->get('/excluir', 'App:deleteUser', 'app.deleteUser');
$router->post('/senha', 'App:updatePassword', 'app.updatePassword');

//crud product
$router->group('/produto');
$router->post("/novo", "App:createProduct", "app.createProduct");
$router->get("/novo", "App:createProduct", "app.createProduct");
$router->post("/editar", "App:editProduct", "app.editProduct");
$router->post("/atualizar-capa-produto", "App:updateCover", "app.updateCover");
$router->post("/deletar", "App:deleteProduct", "app.deleteProduct");

$router->group(null);
$router->get("/teste", "Web:test", "web.test");
$router->post("/teste", "Web:test", "web.test");

$router->group(null);
$router->get("/erro", "Web:error", "web.error");

$router->dispatch();

if ($router->error()) {
    $router->redirect('/erro');
}
ob_end_flush();
