<?php

require __DIR__ . "/vendor/autoload.php";

use App\Http\Router;
use App\Utils\View;

define('URL', 'http://localhost/MagazordTest');

View::init(['URL' => URL]);

$router = new Router(URL);

include __DIR__.'/routes/web.php';

$router->run()->sendResponse();
