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

$router->get('/sobre', [
    function() {
        return new Response(200, HomeController::about());
    }
]);

$router->get('/pagina/{idPagina}/{acao}', [
    function($idPagina, $acao) {
        return new Response(200, 'Página'.$idPagina.'-'.$acao);
    }
]);