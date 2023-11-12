<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . "/bootstrap/app.php";

use App\Http\Router;
use App\App;

$router = new Router(URL);

include __DIR__.'/routes/web.php';

$app = new App($router);
$app->run();
//$router->run()->sendResponse();
