<?php

namespace App\Http;

use Closure;
use Exception;

class Router
{
    /**
     * URL completa
     * @var string
     */
    private $url = '';

    /**
     * Prefixo das rotas
     * @var string
     */
    private $prefix = '';

    /**
     * Índice de rotas
     * @var array
     */
    private $routes = [];

    /**
     * Instância da request
     * @var Request
     */
    private $request;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->request = new Request();
        $this->url = $url;
        $this->setPrefix();
    }

    /**
     * Método responsável por adicionar o prefixo das rotas
     * @return void
     */
    private function setPrefix()
    {
        $parseUrl = parse_url($this->url);
        $this->prefix = $parseUrl['path'] ? $parseUrl['path'] : '';

    }

    /**
     * Adiciona uma rota na classe
     * @param $method
     * @param $route
     * @param $params
     * @return void
     */
    private function addRoute($method, $route, $params = [])
    {
        //Validação dos parâmetros
        foreach ($params as $key => $value) {
            if($value instanceof Closure) {
                //Troca posição numérica do array para uma chave controller
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        //Regex para validação da URL
        $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';

        $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * Método responsável por definir uma rota de get
     * @param $route
     * @param $params
     * @return void
     */
    public function get($route, $params = [])
    {
        return $this->addRoute('GET', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de post
     * @param $route
     * @param $params
     * @return void
     */
    public function post($route, $params = [])
    {
        return $this->addRoute('POST', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de put
     * @param $route
     * @param $params
     * @return void
     */
    public function put($route, $params = [])
    {
        return $this->addRoute('PUT', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de delete
     * @param $route
     * @param $params
     * @return void
     */
    public function delete($route, $params = [])
    {
        return $this->addRoute('DELETE', $route, $params);
    }

    /**
     * Retorna a URI desconsiderando o prefix
     * @return string
     */
    private function getUri()
    {
        $uri = $this->request->getUri();
        //Divide a uri com o prefix
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];
        return end($xUri);
    }

    /**
     * Retorna os dados da rota atual
     * @return array
     */
    private function getRoute()
    {
        $uri = $this->getUri();
        $method = $this->request->getHttpMethod();

        $httpMethod = $this->request->getHttpMethod();
        foreach ($this->routes as $patternRoute => $methods) {
            if(preg_match($patternRoute, $uri)) {
                //Valida o método
                if($methods[$httpMethod]) {
                    return $methods[$httpMethod];
                }
                throw new Exception("Método não permitido", 405);
            }
        }
        throw new Exception("URL não encontrada", 404);
    }

    /**
     * Execução da rota
     * @return Response
     */
    public function run()
    {
        try {
            $route = $this->getRoute();
            if(!isset($route['controller'])) {
                throw new Exception('URL não pôde ser processada', 500);
            }

            $args = [];
            return call_user_func_array($route['controller'], $args);
        } catch (Exception $exception) {
            return new Response($exception->getCode(), $exception->getMessage());
        }
    }
}