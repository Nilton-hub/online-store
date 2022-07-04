<?php

namespace src\core;

use League\Plates\Engine;
use CoffeeCode\Router\Router;

class Controller
{
    /** @var Engine */
    protected $templates;

    /** @var Router */
    protected $router;

    /**
     * Controller constructor.
     * @param $router
     * @param string|null $dir
     * @param array $globals
     */
    public function __construct($router, string $dir = null, array $globals = []) {
        $dir = ($dir ?? VIEWS_PATH);
        $this->templates = new Engine($dir, 'php');
        $this->router = $router;
        $this->templates->addData(['router' => $this->router]);

        if ($globals) {
            $this->templates->addData($globals);
        }
    }
}
