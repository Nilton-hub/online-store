<?php

namespace src\models;
use CoffeeCode\DataLayer\DataLayer;
use \Exception;

/**
 * Class Product
 * @package src\models
 */
class Product extends DataLayer
{
    /**
     * Product constructor.
     */
    public function __construct() {
        parent::__construct('products', ['user_id', 'category_id', 'name', 'description', 'cover', 'price']);
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->isPrice() || !parent::save()) {
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function isPrice(): bool
    {
        $this->price = str_replace(',', '.', $this->price);
        if (!is_numeric($this->price)) {
            $this->fail = new Exception("Digite o preço com reais e centavos separados por virgula. &#128521;");
            return false;
        }
        $this->price = (float)$this->price;
        return true;
    }

    /**
     * @return User
     */
    public function user(): User
    {
        return (new User())->findById($this->user_id);
    }

    public function category(): Category
    {
        return (new Category())->findById($this->category_id);
    }
}
