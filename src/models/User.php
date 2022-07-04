<?php

namespace src\models;

use \CoffeeCode\DataLayer\DataLayer;
use CoffeeCode\DataLayer\Connect;
use \Exception;

/**
 * Class User
 * @package src\models
 */
class User extends DataLayer
{
    /**
     * User constructor.
     */
    public function __construct() {
        parent::__construct('users', ['name', 'email', 'password', 'phone']);
    }

    /**
     * @param string $email
     * @param string $columns
     * @return $this|null
     */
    public function findByEmail(string $email, string $columns = '*'): ?self
    {
        return self::find('email = :e', "e={$email}", $columns);
    }

    /**
     * @param string $token
     * @param string $columns
     * @return $this|null
     */
    public function findByToken(string $token, string $columns = '*'): ?self
    {
        $stmt = Connect::getInstance()->query("SELECT id FROM users WHERE token_login = '{$token}'");
        if ($stmt->rowCount()) {
            $user = $stmt->fetch();
            return $this->findById($user->id);
        }
        return null;
    }

    /**
     * @return bool
     */
    protected function validateEmail(): bool
    {
        if (empty($this->email)) {
            $this->fail = new Exception("Você precisa informar um email para se cadastrar. &#128521;");
            return false;
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->fail = new Exception("O email informado é inválido.");
            return false;
        }

        $email = null;
        if ($this->id) {
            $email = $this->find('email = :e AND id != :i', "e={$this->email}&i={$this->id}")->count();
        } else {
            $email = $this->findByEmail($this->email)->count();
        }

        if ($email) {
            $this->fail = new Exception("O email informado já está cadastrado. Você pode tentar outro email.  &#128521;");
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function validatePassword(): bool
    {
        if (password_get_info($this->password)['algo']) {
            return true;
        }

        if (empty($this->password)) {
            $this->fail = new Exception("Você precisa informar uma senha para se cadastrar.");
            return false;
        }

        if (mb_strlen($this->password) < PASSWORD_MIN_LEN || mb_strlen($this->password) > PASSWORD_MAX_LEN) {
            $this->fail = new Exception("A sua senha precisa ter entre 8 e 40 caracteres.");
            return false;
        }
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return true;
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->validateEmail() || !$this->validatePassword() || !parent::save()) {
            return false;
        }
        return true;
    }
}
