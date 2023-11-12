<?php

namespace App;

use App\Http\Router;
use App\Utils\View;


class App
{
    public function __construct(protected Router $router) {}

    public function run()
    {
        View::init(['URL' => getenv('URL')]);
        $this->router->run()->sendResponse();
    }
}