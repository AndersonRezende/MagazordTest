<?php

namespace App\Controller;

use App\Model\Entity\Person;
use \App\Utils\View;

class HomeController
{
    public static function index()
    {
        $person = new Person();
        return View::render('home', [
            'name' => $person->name,
            'cpf' => $person->cpf
        ]);
    }

    public static function about()
    {
        return View::render('about', []);
    }
}