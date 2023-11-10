<?php
//require __DIR__ . "/../vendor/autoload.php";

use App\Controller\HomeController;
use App\Http\Response;
use App\Http\Router;


$router = new Router(URL);

$router->get('/', [
    function() {
        return new Response(200, HomeController::index());
    }
]);
