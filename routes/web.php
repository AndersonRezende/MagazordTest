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

$router->get('/pessoas', [
    function() {
        return new Response(200, \App\Controller\PersonController::index());
    }
]);

$router->post('/pessoas', [
    function($request) {
        return new Response(200, \App\Controller\PersonController::store($request));
    }
]);

$router->get('/pagina/{idPagina}/{acao}', [
    function($idPagina, $acao) {
        return new Response(200, 'Página'.$idPagina.'-'.$acao);
    }
]);