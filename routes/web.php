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

$router->get('/pessoas/novo', [
    function() {
        return new Response(200, \App\Controller\PersonController::create());
    }
]);

$router->get('/pessoas/editar/{id}', [
    function($id) {
        return new Response(200, \App\Controller\PersonController::edit($id));
    }
]);

$router->post('/pessoas/atualizar/{id}', [
    function($request, $id) {
        return new Response(200, \App\Controller\PersonController::update($request, $id));
    }
]);

$router->get('/pessoas/{id}', [
    function($id) {
        return new Response(200, \App\Controller\PersonController::show($id));
    }
]);

$router->post('/destroy/{id}', [
    function($id) {
        return new Response(200, \App\Controller\PersonController::destroy($id));
    }
]);

$router->post('/pessoas', [
    function($request) {
        return new Response(200, \App\Controller\PersonController::store($request));
    }
]);




$router->get('/contatos', [
    function() {
        return new Response(200, \App\Controller\ContactController::index());
    }
]);

$router->get('/contatos/novo', [
    function() {
        return new Response(200, \App\Controller\ContactController::create());
    }
]);

$router->get('/contatos/editar/{id}', [
    function($id) {
        return new Response(200, \App\Controller\ContactController::edit($id));
    }
]);

$router->post('/contatos/atualizar/{id}', [
    function($request, $id) {
        return new Response(200, \App\Controller\ContactController::update($request, $id));
    }
]);

$router->get('/contatos/{id}', [
    function($id) {
        return new Response(200, \App\Controller\ContactController::show($id));
    }
]);

$router->post('/destroy-c/{id}', [
    function($id) {
        return new Response(200, \App\Controller\ContactController::destroy($id));
    }
]);

$router->post('/contatos', [
    function($request) {
        return new Response(200, \App\Controller\ContactController::store($request));
    }
]);

$router->get('/pagina/teste/{idPagina}', [
    function($idPagina) {
        return new Response(200, 'Página'.$idPagina.'-');
    }
]);