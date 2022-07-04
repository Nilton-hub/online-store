<?php

namespace src\models;
use CoffeeCode\DataLayer\DataLayer;

/**
 * Class Auth
 * @package src\models
 */
class Auth extends DataLayer
{
    /** @var ?string $message */
    private ?string $message = null;

    /**
     * Auth constructor.
     */
    public function __construct() {
        parent::__construct('user', ['email', 'password']);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function register(User $user): bool
    {
        if (!$user->save) {
            $this->message = $user->fail()->getMessage();
            return false;
        }
        return true;
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool $save
     * @return bool
     */
    public function login(string $email, string $password, bool $save = false): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->message = "O email informado é inválido &#128553;";
            return false;
        }

        $token = token(30);
        if ($save) {
            setcookie('authToken', $token, time() + 86400, '/');
        } else {
            setcookie('authToken', null, time() - 3600, '/');
        }

        $user = (new User())->findByEmail($email)->fetch();
        if (!$user) {
            $this->message = "O email ou a senha não confere. &#128543;";
            return false;
        }

        if (!password_verify($password, $user->password)) {
            $this->message = "O email ou a senha não confere. &#128543;";
            return false;
        }

        if (password_get_info($user->password)['algo']) {
            $user->password = password_hash($password, PASSWORD_DEFAULT);
        }
        $user->token_login = $token;
        $user->save();
        $_SESSION['authToken'] = $token;
        return true;
    }

    /**
     * @return User|null
     */
    public static function user(): ? User
    {
        if (!empty($_SESSION['authToken'])) {
            $user = (new User())->findByToken($_SESSION['authToken']);

            if ($user) {
                return $user;
            }
        }

        if (!empty($_COOKIE['authToken'])) {
            $user = (new User())->findByToken($_COOKIE['authToken']);
            if ($user) {
                $_SESSION['authToken'] = $_COOKIE['authToken'];
                return $user;
            }
        }
        setcookie('authToken', null, time() - 3600, '/');
        unset($_SESSION['authToken']);
        return null;
    }

    public function forget(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->message = "O email informado não é válido.";
            return false;
        }
        
        $user = (new User())->findByEmail($email)->fetch();
        
        if (!$user) {
            $this->message = "O email informado não está cadastrado.";
            return false;
        }

        $user->forget = md5(uniqid(rand(), true));
        $user->save();

        $usernamelink = url("/recuperar/{$user->forget}|{$user->email}");
        $messageHtml = (new \League\Plates\Engine(dirname(__DIR__, 1) . '/views/shared/', 'php'))
            ->render('mail-forget', [
                'username' => $user->name,
                'usernamelink' => $usernamelink
            ]);

        return mail(
            $user->email,
            "Recupere sua senha no Online Sotore",
            $messageHtml,
            "From: " . MAIL['from_email'] . "\ncontent-type: text/html; charset=\"utf-8\"\n\n"
        );
        //return true; //(remover)
    }

    public function reset(string $email, string $code, string $password, string $passwordRe): bool
    {
        $user = (new User())->findByEmail($email)->fetch();
        
        if (!$user) {
            $this->message = "O email informado não está cadastrado. A conta que corresponde à <b>{$email}</b> não existe.";
            return false;
        }
        
        if ($user->forget != $code) {
            $this->message = "Desculpe, mas o código de verificação não é válido.";
            return false;
        }

        if (empty($password) || $password != $passwordRe) {
            $this->message = "As senhas informadas não batem. Você precisa repetir a mesma senha";
            return false;
        }

        $user->forget = null;
        $user->password = $password;

        if ($user->save()) {
            return true;
        }
        $this->message = $user->fail()->getMessage();
        return false;
    }

    /**
     * @return string|null
     */
    public function message(): ?string
    {
        return $this->message;
    }
}
