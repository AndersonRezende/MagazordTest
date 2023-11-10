<?php

namespace App\Utils;

class View
{
    private static $yieldPattern = '/@yield\s*(\(\'[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\'\))/';
    private static $layoutPattern = '/@layout\s*(\(\'[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\'\))/';

    /**
     * Variáveis padrões da view
     * @var array
     */
    private static $vars = [];

    /**
     * Dados iniciais da classe
     * @param $vars
     * @return void
     */
    public static function init($vars = [])
    {
        self::$vars = $vars;
    }


    /**
     * Método responsável por retornar o conteudo da view
     * @param string $view
     * @return false|string
     */
    private static function getContentView($view)
    {
        $file = __DIR__.'/../../resources/view/'.$view.'.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }

    /**
     * Método responsável por retornar o conteúdo do layout
     * @param string $layout
     * @return false|string
     */
    private static function getContentLayout($layout)
    {
        $file = __DIR__.'/../../resources/layouts/'.$layout.'.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }

    /**
     * Método responsável por buscar o nome do layout na view
     * @param string $content
     * @return false|string
     */
    private static function getLayoutName($content)
    {
        preg_match(self::$layoutPattern, $content, $matches);
        $layoutName = trim($matches[1]);
        $layoutName = substr($layoutName, 2, strpos($layoutName, ')') - 3);
        return $layoutName;
    }

    /**
     * Método responsável por buscar o layout e unificar com o conteúdo da view
     * @param $contentView
     * @return array|false|string|string[]
     */
    private static function renderWithLayout($contentView)
    {
        $layoutName = self::getLayoutName($contentView);
        $contentView = preg_replace(self::$layoutPattern, '', $contentView);
        $layout = self::getContentLayout($layoutName);
        $layout = str_replace("@yield('content')", $contentView, $layout);
        return $layout;
    }

    /**
     * Método responsável por retornar a view renderizada junto com o layout e as variáveis
     * @param string $view
     * @param array $vars (string/numeric)
     * @return false|string
     */
    public static function render($view, $vars = [])
    {
        $contentView = self::getContentView($view);
        $vars = array_merge(self::$vars, $vars);

        $keys = array_keys($vars);
        // Mapeia os dados separados por chaves na view
        $keys = array_map(function ($item) {
            return '{{'.$item.'}}';
        }, $keys);

        $contentView = str_replace($keys, array_values($vars), $contentView);
        return self::renderWithLayout($contentView);
    }
}