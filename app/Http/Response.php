<?php

namespace App\Http;

class Response
{

    /**
     * Código do status http
     * @var int
     */
    private $httpCode = 200;

    /**
     * Cabeçalho da resposta
     * @var array
     */
    private $headers = [];

    /**
     * Tipo de conteúdo retornado
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Conteúdo da resposta
     * @var mixed
     */
    private $content;

    /**
     * @param int $httpCode
     * @param string $contentType
     * @param mixed $content
     */
    public function __construct($httpCode, $content, $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    /**
     * Método responsável por alterar o content type da resposta
     * @param string $contentType
     * @return void
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Metódo responsável por adicionar um dado no cabeçalho da resposta
     * @param $key
     * @param $value
     * @return void
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    /**
     * Método responsável por enviar a resposta
     * @return void
     */
    public function sendResponse()
    {
        $this->sendHeaders();
        switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
                break;
        }
    }

    /**
     * Método responsável por enviar os headers para o navegador
     * @return void
     */
    private function sendHeaders()
    {
        http_response_code($this->httpCode);
        foreach ($this->headers as $key => $value) {
            header($key.':'.$value);
        }
    }
}