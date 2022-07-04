<?php

namespace src\models;
use CoffeeCode\DataLayer\DataLayer;

/**
 * Class Category
 * @package src\models
 */
class Category extends DataLayer
{
    /**
     * Category constructor.
     */
    public function __construct() {
        parent::__construct('categories', ['name']);
    }
}