<?php

namespace App\Controller;

use \App\Utils\View;

class LayoutController
{
    public static function getLayout()
    {
        return View::render('page', ['name' => 'Anderson Rezende', 'description' => 'Analista de desenvolvimento']);
    }
}