<?php

namespace src\controllers;

use \src\core\Controller;
use src\models\Category;
use src\models\Auth;
use src\support\Upload;
use src\models\Product;
use src\models\User;

/**
 * Class App
 * @package src\controllers
 * @author Nilton Duarte <tvirapegubeco@gmail.com>
 */
class App extends Controller
{
    private $user;

    /**
     * @var Roter $router
     */
    public function __construct($roter)
    {
        parent::__construct($roter);

        if (!$this->user = Auth::user()) {
            $_SESSION['flash']['msg'] = 'É preciso estar logado para acessar esta página.';
            $_SESSION['flash']['type'] = 'alert';
            redirect("/entrar");
        }
    }

    /**
     * @param array $data
     */
    public function createProduct(array $data = []): void
    {
        if ($data) {
            $data = filter_var_array($data, FILTER_SANITIZE_SPECIAL_CHARS, true);
            $imagePath = null;
            $requireds = ['name', 'desc', 'price'];
            foreach ($requireds as $required) {
                if (empty($data[$required])) {
                    echo json_encode(['msg' => 'Preencha os campos necessários. &#128521;', 'type' => 'error']);
                    return;
                }
            }

            $upload = new Upload();
            if ($_FILES && !empty($_FILES['cover']['type'])) {
                $imagePath = $upload->image($_FILES['cover'], "{$this->user->id}-{$_FILES['cover']['name']}");

                if (!$imagePath) {
                    echo json_encode(['msg' => $upload->message(), 'type' => 'alert']);
                    return;
                }
            } else {
                echo json_encode([
                    'msg' => "Selecione uma foto para o produto antes de enviar. &#128521;",
                    'type' => 'error'
                ]);
                return;
            }
            $model = new Product();
            $model->name = $data['name'];
            $model->description = $data['desc'];
            $model->price = $data['price'];
            $model->category_id = (!empty($data['category']) ? $data['category'] : 6);
            $model->cover = $imagePath;
            $model->user_id = $this->user->id;

            if ($model->save()) {
                echo json_encode([
                    'msg' => 'Sucesso! O seu produto aparecerá em nosso feed com os outros produtos. &#128512;',
                    'type' => 'success'
                ]);
                return;
            }
            $upload->remove($model->cover);
            echo json_encode([
                'msg' => $model->fail()->getMessage(),
                'type' => 'error'
            ]);
            return;
        }
        $categories = (new Category())->find(null, null, 'id, name')->order('`order`')->fetch(true);

        echo $this->templates->render('app/create', [
            'title' => 'Publicar Produto',
            'categories' => $categories
        ]);
    }

    /**
     * @param array $data
     */
    public function editProduct(array $data): void
    {
        if ($data && !empty($data['id'])) {
            $id = filter_var($data['id'], FILTER_VALIDATE_INT);
            $model = (new Product())->findById($id);
            if (!$model) {
                echo json_encode([
                    'msg' => 'Erro, este produto não existe.',
                    'type' => 'error'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                return;
            }
            $name = filter_var($data['name'], FILTER_SANITIZE_SPECIAL_CHARS);
            $price = filter_var($data['price'], FILTER_SANITIZE_SPECIAL_CHARS);
            $category = (!empty($data['category']) && is_numeric($data['category']) ? $data['category'] : null);
            $category = filter_var($category, FILTER_VALIDATE_INT);
            $desc = filter_var($data['desc'], FILTER_SANITIZE_SPECIAL_CHARS);

            $model->category_id = (!empty($category) ? $category : $model->category_id);
            $model->name = (!empty($name) ? $name : $model->name);
            $model->description = (!empty($desc) ? $desc : $model->description);
            $model->price = (!empty($price) ? $price : $model->price);

            $msg = null;
            $type = 'success';
            if (!empty($_FILES['cover']["type"])) {
                $upload = new Upload();
                $path = $upload->image($_FILES['cover'], "{$this->user->id}-{$_FILES['cover']['name']}");
                
                if ($path) {
                    $upload->remove(__DIR__ . "/../../$model->cover");
                    $model->cover = $path;
                } else {
                    $msg = ', mas ' . lcfirst($upload->message());
                    $type = 'alert';
                }
            }

            if ($model->save()) {
                $model->cover = url("/{$model->cover}");
                echo json_encode([
                    'msg' => 'Produto atualizado com sucesso' . ($msg ?? '.'),
                    'type' => $type,
                    'data' => [
                        'id' => $model->id,
                        'category' => $model->category_id,
                        'name' => $model->name,
                        'description' => $model->description,
                        'price' => $model->price,
                        'cover' => $model->cover
                    ]
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                return;
            }
            echo json_encode([
                'msg' => $model->fail()->getMessage(),
                'type' => 'error'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            return;
        }
        echo json_encode([
            'msg' => 'Desculpe, não foi possível completar a sua solicitação.',
            'type' => 'error'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function updateCover(array $data = null)
    {
        if ($data && !empty($data['id'])) {
            $id = filter_var($data['id'], FILTER_VALIDATE_INT);
            $model = (new Product())->findById($id);

            if (!$model) {
                echo json_encode([
                    'msg' => 'Erro, este produto não existe.',
                    'type' => 'error'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                return;
            }

            if (!empty($_FILES['cover']["type"])) {
                $upload = new Upload();
                $path = $upload->image($_FILES['cover'], "{$this->user->id}-{$_FILES['cover']['name']}");
                
                if ($path) {
                    $upload->remove(__DIR__ . "/../../$model->cover");
                    $model->cover = $path;
                } else {
                    $msg = $upload->message();
                    $type = 'alert';
                }
                
                if ($model->save()) {
                    $model->cover = url("/{$model->cover}");
                    echo json_encode([
                        'msg' => 'Produto atualizado com sucesso.',
                        'type' => 'success',
                        'data' => [
                            'id' => $model->id,
                            'cover' => $model->cover
                        ]
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    return;
                }
            }
        }
        echo json_encode([
            'msg' => 'Desculpe, não foi possível completar a sua solicitação.',
            'type' => 'error'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param array $data
     */
    public function deleteProduct(array $data): void
    {
        if ($data) {
            $id = filter_var($data['id'], FILTER_VALIDATE_INT);
            $model = (new Product())->findById($id);
            if (!$model) {
                echo json_encode([
                    'msg' => 'Você tentou deletar um produto não cadastrado',
                    'type' => 'error'
                ]);
                return;
            }
            $cover = $model->cover;

            if ($model->destroy()) {
                (new Upload())->remove(__DIR__ . "/../../{$cover}");
                echo json_encode([
                    'msg' => 'Publicação deletada com sucesso.',
                    'type' => 'success'
                ]);
                return;
            }
        }
        echo json_encode([
            'msg' => 'Desculpe, mas não foi possível processar a sua solicitação.',
            'type' => 'error'
        ]);
        return;
    }

    public function updatePassword(array $data): void
    {
        if (!$data || in_array('', $data)) {
            echo json_encode([
                'msg' => "Preencha todos os campos para alteração de senha.",
                'type' => 'error'
            ]);
            return;
        }
        $user = (new User())->findById($this->user->id);

        if (!password_verify($data["passwd_actual"], $user->password)) {
            echo json_encode([
                'msg' => "Não foi possível alterar a senha. A senha atual está incorreta.",
                'type' => 'error'
            ]);
            return;
        }

        if ($data['passwd'] != $data['passwd_re']) {
            echo json_encode([
                'msg' => "As senhas não batem! Você precisa inserir e repetir a mesma senha para alterá-la.",
                'type' => 'error'
            ]);
            return;
        }

        $user->password = $data['passwd'];
        if (!$user->save()) {
            echo json_encode([
                'msg' => $user->fail()->getMessage(),
                'type' => 'error'
            ]);
            return;
        }
        echo json_encode([
            'msg' => "A sua senha foi alterada com sucesso.",
            'type' => 'success'
        ]);
    }

    /**
     * @param array $data
     */
    public function editUser(array $data): void
    {
        if ($data) {
            $user = (new User())->findById($this->user->id);
            $user->name = (!empty($data['name']) ? $data['name'] : $this->user->name);
            $user->email = (!empty($data['email']) ? $data['email'] : $this->user->email);
            $user->phone = (
                !empty($data['phone']) ?
                    preg_replace('/[^0-9]/','', $data['phone']) :
                    preg_replace('/[^0-9]/','', $this->user->phone)
            );

            if (!$user->save()) {
                echo json_encode([
                    'msg' => $user->fail()->getMessage(),
                    'type' => 'error'
                ]);
                return;
            }
            echo json_encode([
                'msg' => "Atualização realizada com sucesso!",
                'type' => 'success',
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => phone($user->phone)
                ]
            ]);
            return;
        }
    }

    /**
     * @param array $data
     */
    public function deleteUser(array $data): void
    {
        if (!empty($data['delete']) && $this->user->destroy()) {
            echo json_encode([
                'msg' => 'Exclusão bem sucedida!',
                'type' => 'success',
                'data' => $this->user->data()
            ]);
            return;
        }
        echo $this->templates->render('app/delete-user', [
            'title' => 'Excuir Conta'
        ]);
    }
    /**
     *
     */
    public function profile(): void
    {
        $products = new Product();
        $user = new User();
        $category = new Category();
        $pdtList = function () use ($products) {
            $list = ($products->find("user_id = :user", "user={$this->user->id}")->order('created_at DESC')->fetch(true) ?? []);
            foreach ($list as $product) {
                yield $product;
            }
        };
        $categoryList = function () use ($category) {
            $categories = $category->find()->order('`order`')->fetch(true);
            foreach ($categories as $cat) {
                yield $cat;
            }
        };

        echo $this->templates->render('app/profile', [
            'title' => 'Produto',
            'products' => ($products->find("user_id = :user", "user={$this->user->id}")->count() > 0 ? $pdtList() : null),
            'user' => $user->findById($this->user->id),
            'categories' => $categoryList()
        ]);
    }

    /**
     *
     */
    public function logout(): void
    {
        session_unset();
        session_destroy();
        $this->user->token_login = null;
        $this->user->save();
        
        if (isset($_COOKIE['authToken'])) {
            setcookie('authToken', null, time() - 3600, '/');
        }
        $_SESSION['flash']['msg'] = "Você saiu com sucesso {$this->user->name}, volte logo! &#128527;";
        $_SESSION['flash']['type'] = 'success';
        redirect();
    }
}
