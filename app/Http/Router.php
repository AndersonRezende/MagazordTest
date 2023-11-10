<?php

namespace App\Http;

use Closure;
use Exception;
use ReflectionFunction;

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
        $this->prefix = $parseUrl['path'] ?? '';

    }

    /**
     * Adiciona uma rota na classe.
     * @param $method <p>
     * Método de requisição http.
     * </p>
     * @param $route <p>
     * Rota de acesso.
     * </p>
     * @param $params <p>
     * Função (controlador ou anônima) que executará a ação.
     * </p>
     * @return void
     */
    private function addRoute($method, $route, $params = [])
    {
        // Verifica se existe uma closure (função anônima) e, caso exista, adiciona o índice controller para ela.
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
            }
        }

        // Define um índice para variáveis da rota. Ex: dominio.com/pessoa/10
        $params['variables'] = [];

        // Regex para validação da existência de variáveis na rota.
        // Caso exista, a lista de variáveis da rota são armazenadas em um índice e suas respectivas ocorrências
        // na rota serão substituidas por um regex equivalente.
        $patternVariable = '/{(.*?)}/';
        if (preg_match_all($patternVariable, $route, $matches)) {
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        // Faz a adaptação para adequar o regex para validação da URL
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
        // Divide a uri com o prefix
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
        $httpMethod = $this->request->getHttpMethod();
        foreach ($this->routes as $patternRoute => $methods) {
            // Verifica se alguma das regex criadas na definição da rota é compatível com a URI informada
            if (preg_match($patternRoute, $uri, $matches)) {
                // Caso seja compatível, verifica se o verbo html também é compatível
                if (isset($methods[$httpMethod])) {
                    // Remove a posição inicial que contém a uri completa
                    unset($matches[0]);

                    // Variáveis processadas
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

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
            if (!isset($route['controller'])) {
                throw new Exception('URL não pôde ser processada', 500);
            }

            // Argumentos da função
            $args = [];

            // Reflection para obter dados ("descompilação") da função
            // Receberá o controlador para conseguir obter os dados da função
            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            return call_user_func_array($route['controller'], $args);
        } catch (Exception $exception) {
            return new Response($exception->getCode(), $exception->getMessage());
        }
    }
}