<?php

require __DIR__ . "/vendor/autoload.php";

use App\Http\Router;

define('URL', 'http://localhost/MagazordTest');

$router = new Router(URL);

include __DIR__.'/routes/web.php';

$router->run()->sendResponse();
