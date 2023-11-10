<?php

namespace App\Http;

class Request
{
    /**
     * Método http da requisição
     * @var string
     */
    private $httpMethod;

    /**
     * Rota da página
     * @var string
     */
    private $uri;

    /**
     * Parâmetros da URL ($_GET)
     * @var array
     */
    private $queryParams = [];

    /**
     * Variáveis recebidas do POST da página ($_POST)
     * @var array
     */
    private $postVars = [];

    /**
     * Cabeçalho da requisição
     * @var array
     */
    private $headers = [];


    /**
     * Construtor da classe
     */
    public function __construct()
    {
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';
    }

    /**
     * @return mixed|string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * @return mixed|string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * @return array
     */
    public function getPostVars()
    {
        return $this->postVars;
    }

    /**
     * @return array|false
     */
    public function getHeaders()
    {
        return $this->headers;
    }


}