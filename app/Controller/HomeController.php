<?php

namespace App\Controller;

use App\Entity\Person;
use App\Utils\View;

class HomeController
{
    public static function index()
    {
        $person = new Person();
        return View::render('home', [
            'name' => "Anderson Rezende",
            'cpf' => "123.456.789-10"
        ]);
    }

    public static function about()
    {
        return View::render('about', []);
    }
}