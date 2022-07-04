<?php

namespace src\controllers;

use \src\core\Controller;
use \src\models\User;
use \src\models\Auth;
use \src\models\Product;
use \CoffeeCode\Paginator\Paginator;
use CoffeeCode\DataLayer\Connect;
use PDOException;

/**
 * Class Web
 * @package src\controllers
 */
class Web extends Controller
{
    /**
     * Web constructor.
     * @param $router
     */
	function __construct($router) {
        parent::__construct($router);
	}

    /**
     *
     */
    public function home(array $data): void
    {
        $page = filter_var((!empty($data["page"]) ? $data["page"] : 1), FILTER_VALIDATE_INT);
        $pager = new Paginator(url() . "/", "Página", ["Primeira página", '<i class="fas fa-chevron-left"></i>'], ["Última página", '<i class="fas fa-chevron-right"></i>']);
        $models = new Product();
        $total = $models->find()->count();
        $pager->pager($total, 10, $page, 2);

        $products = function() use ($models, $pager) {
            $list = $models->find()->limit($pager->limit())->offset($pager->offset())->order('created_at')->fetch(true);
            foreach ($list as $model) {
                yield $model;
            }
        };

        echo $this->templates->render('web/home', [
            'title' => 'Ofertas',
            'products' => $products(),
            'pager' => $pager->render(),
            'total' => $total
        ]);
    }

    /**
     * @var array $data
     */
    public function searchPost(array $data): void
    {
        if (!empty($data["search"])) {
            $terms = filter_var($data['search'], FILTER_SANITIZE_STRIPPED);
            $page = filter_var((!empty($data["page"]) ? $data["page"] : 1), FILTER_VALIDATE_INT);
            $model = (new Product())
                ->find("MATCH(name, description) AGAINST(:s) OR category_id = (SELECT id FROM categories WHERE NAME = :s)", "s={$terms}")
                ->order('name, description');
            $total = $model->count();

            $pager = new Paginator(url("/encontrar/{$terms}/"), "Página",["Primeira página", '<i class="fas fa-chevron-left"></i>'], ["Última página", '<i class="fas fa-chevron-right"></i>']);
            $pager->pager($total, 10, $page, 2);

            $results = function () use ($model, $pager)
            {
                $list = ($model->limit($pager->limit())->offset($pager->offset())->fetch(true) ?? []);
                foreach ($list as $result) {
                    yield $result;
                }
            };

            echo $this->templates->render('web/search-results', [
                'title' => "Pesquisa",
                'terms' => $terms,
                'results' => $results(),
                'total' => $total,
                'pager' => $pager->render()
            ]);
            return;
        }
        redirect();
    }

    function searchSuggestions(array $data): void
    {
        if (!empty($data['search'])) {
            try {
                $terms = filter_var($data['search'], FILTER_SANITIZE_STRIPPED);
                $stmt = Connect::getInstance()->prepare("
                    SELECT name FROM products WHERE MATCH(name, description) AGAINST(:terms) 
                        OR
                    category_id = (SELECT id FROM categories WHERE NAME = :terms)
                        UNION 
                    SELECT name FROM categories WHERE name = :terms;            
                ");
                $stmt->bindValue(':terms', $terms);
                $stmt->execute();
                echo json_encode($stmt->fetchAll());
            } catch (PDOException $exception) {
                return;
            }
        }
    }

    /**
     * @param array $data
     */
    public function login(array $data = []): void
    {
        if (!empty($_SESSION['authToken'])) {
            $ref = filter_input(INPUT_SERVER, 'HTTP_REFERER');
            redirect(($ref ?? '/'));
        }
        if ($data) {
            $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
            $password = filter_var($data['password'], FILTER_SANITIZE_STRING);
            $remember = (!empty($data['remember']) ? true : false);
            $login = new Auth();

            if ($login->login($email, $password, $remember)) {
                $_SESSION['flash']['msg'] = 'Login efetuado com sucesso!';
                $_SESSION['flash']['type'] = 'success';
                echo json_encode([
                    'redirect' => $this->router->route("web.home")
                ],
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    return;
                    
            }
            echo json_encode([
                'msg' => $login->message(),
                'type' => 'error'
            ],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            return;
        }

        echo $this->templates->render('web/login', [
            'title' => 'Login'
        ]);
    }

    /**
     * @param array $data
     */
    public function register(array $data = []): void
    {
        if (!empty($_SESSION['authToken'])) {
            $ref = filter_input(INPUT_SERVER, 'HTTP_REFERER');
            redirect(($ref ?? '/'));
        }
        if ($data) {
            $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
            $data = filter_var_array($data, [
                'name' => FILTER_SANITIZE_SPECIAL_CHARS,
                'email' => FILTER_VALIDATE_EMAIL,
                'phone' => FILTER_VALIDATE_INT,
                'password' => FILTER_SANITIZE_SPECIAL_CHARS,
                'password_re' => FILTER_SANITIZE_SPECIAL_CHARS,
                'action' => FILTER_SANITIZE_SPECIAL_CHARS
            ]);
            
            if ($data['password_re'] != $data['password']) {
                echo json_encode([
                    "msg" => "Informe e repita sua senha. Você digitou duas senhas diferente. &#128529;",
                    "type" => "error"
                ]);
                return;
            }
            
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'];
            $user->password = $data['password'];
            $auth = new Auth();
            
            if ($auth->register($user)) {
                echo json_encode([
                    'msg' => 'Usuário cadastrado com sucesso. &#128515;',
                    "type" => "success"
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                return;
            }
            echo json_encode([
                'msg' => $auth->message(),
                'type' => 'alert'
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            return;
        }

        echo $this->templates->render('web/register', [
            'title' => 'Criar Conta'
        ]);
    }

    public function forget(?array $data): void
    {
        if (!empty($_SESSION['authToken'])) {
            redirect(filter_input(INPUT_SERVER, 'HTTP_REFERER') ?? '/');
        }

        if (isset($data['email'])) {
            if (empty($data['email'])) {
                echo json_encode([
                    'msg' => 'Informe um email para continuar',
                    'type' => 'error'
                ]);
                return;
            }
            $auth = new Auth();
            $auth->email = '';
            $auth->password = '';

            if (!$auth->forget($data['email'])) {
                echo json_encode([
                    'msg' => $auth->message(),
                    'type' => 'error'
                ]);
                return;
            }
            echo json_encode([
                'msg' => 'Tudo pronto! Enviamos um email com um link para você redefinir a sua senha. &#128521;',
                'type' => 'error'
            ]);
            return;
        }
        echo $this->templates->render('web/forget', [
            'title' => 'Recuperar'
        ]);
    }

    /**
     * @var array
     */
    public function reset(array $data): void
    {
        if (!empty($_SESSION['authToken'])) {
            redirect((filter_input(INPUT_SERVER, 'HTTP_REFERER') ?? '/'));
        }

        if (!empty($data['password_reset'])) {
            if (empty($data['password']) || empty($data['password_re'])) {
                echo json_encode([
                    'msg' => 'Informe e repita a sua senha para continuar.',
                    'type' => 'error'
                ]);
                return;
            }
            
            list($code, $email) = explode("|", $data['token']);
            $auth = new Auth();

            if (!$auth->reset($email, $code, $data['password'], $data['password_re'])) {
                echo json_encode([
                    'msg' => $auth->message(),
                    'type' => 'error'
                ]);
                return;
            }

            $_SESSION['flash']['msg'] = 'Senha alterada com sucesso!';
            $_SESSION['flash']['type'] = 'success';
            echo json_encode([
                // 'msg' => 'Senha alterada com sucesso.',
                // 'type' => 'error',
                'redirect' => url('/entrar')
            ]);
            return;
        }
        echo $this->templates->render('web/reset', [
            'title' => 'Nova Senha',
            'code' => $data['token']
        ]);
    }
    
    /**
     * 
     */
    public function error(): void
    {
        echo $this->templates->render('web/error');
    }

    /**
     * @param array $data
     */
    public function test(array $data = []): void
    {
        // echo '<p><a href="http://localhost/projects/online-store/debug.php">Voltar</a></p>';
        if ($data) {
            $data = filter_var_array($data, [
                'name' => FILTER_SANITIZE_SPECIAL_CHARS,
                'email' => FILTER_VALIDATE_EMAIL,
                'phone' => FILTER_SANITIZE_SPECIAL_CHARS,
                'password' => FILTER_SANITIZE_SPECIAL_CHARS,
                'password_re' => FILTER_SANITIZE_SPECIAL_CHARS,
                'a' => FILTER_SANITIZE_SPECIAL_CHARS
            ]);
            
            $user = new User();
            if (empty($data['password']) || empty($data['password_re']) || $data['password'] != $data['password_re']) {
                //echo '<h2>ERRO! Você precisa informar e repetir a mesma senha.</h2>';
                json_encode(["msg" => 'ERRO! Você precisa informar e repetir a mesma.']);
                return;
            }
            
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'];
            $user->password = $data['password'];
            
            $auth = new Auth();
            
            if ($auth->register($user)) {
                echo json_encode([
                    'msg' => 'Usuário cadastrado com sucesso. &#128515;',
                    "type" => "success"
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                return;
            }
            echo json_encode([
                'msg' => $auth->message(),
                'type' => 'alert'
            ]);
            return;
        }
        echo $this->templates->render('shared/mail-forget');
    }
}
