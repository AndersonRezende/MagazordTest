<?php

namespace App\Controller;


use App\Factory\EntityManagerFactory;
use App\Http\Request;
use App\Entity\Person;
use App\Utils\View;

class PersonController
{
    public static function index()
    {
        $entityManager = EntityManagerFactory::getEntityManager();
        $personRepository = $entityManager->getRepository(Person::class);
        $people = $personRepository->findAll();

        $item = '';
        foreach ($people as $person) {
            $item .= View::renderPartial('person/partial/item', [
               'id' => $person->getId(),
               'name' => $person->getName(),
               'cpf' => $person->getCpf()
            ]);
        }
        return View::render('person/index', [
            'name' => "Anderson Rezende",
            'cpf' => "123.456.789-10",
            'item' => $item
        ]);
    }

    public static function store(Request $request)
    {
        //var_dump($request);
    }
}