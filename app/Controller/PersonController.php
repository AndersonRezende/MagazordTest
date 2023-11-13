<?php

namespace App\Controller;


use App\Factory\EntityManagerFactory;
use App\Http\Request;
use App\Entity\Person;
use App\Http\Router;
use App\Utils\View;
use App\Validator\PersonValidator;

class PersonController
{
    public static function index()
    {
        $people = self::getRepository()->findAll();

        $item = '';
        foreach ($people as $person) {
            $item .= View::renderPartial('person/partial/table-item', [
               'id' => $person->getId(),
               'name' => $person->getName(),
               'cpf' => $person->getCpf(),
               'contacts' => $person->getContacts()->count()
            ]);
        }
        return View::render('person/index', [
            'item' => $item
        ]);
    }

    public static function show($id)
    {
        $person = self::getRepository()->find($id);
        if($person) {

            $item = '';
            foreach ($person->getContacts() as $contact) {

                $item .= View::renderPartial('person/partial/contact-item', [
                    'id' => $contact->getId(),
                    'type' => ucfirst($contact->getType()),
                    'description' => $contact->getDescription()
                ]);
            }

            return View::render('person/show', [
                'id' => $person->getId(),
                'name' => $person->getName(),
                'cpf' => $person->getCpf(),
                'qtd_contacts' => $person->getContacts()->count(),
                'contact-item' => $item
            ]);
        }
        return View::render('error', [
           'msg' => 'Não foi possível localizar o recurso informado.'
        ]);
    }

    public static function create()
    {
        return View::render('person/create', []);
    }

    public static function store(Request $request)
    {
        $validate = PersonValidator::validate($request->getPostVars());
        $person = new Person();
        if(!$validate) {
            $person->setName($request->getPostVars()['name']);
            $person->setCpf($request->getPostVars()['cpf']);
            $entityManager = EntityManagerFactory::getEntityManager();
            try {
                $entityManager->persist($person);
                $entityManager->flush();

                Router::redirect('/pessoas');
            } catch (\Exception $e) {
                return View::render('error', ['msg' => 'Não foi possível gravar o recurso informado.']);
            }
        }
        return View::render('error', ['msg' => 'Erros de validação foram encontrados: '.$validate]);
    }

    public static function edit($id)
    {
        $person = self::getRepository()->find($id);
        return View::render('person/edit', [
            'id' => $person->getId(),
            'name' => $person->getName(),
            'cpf' => $person->getCpf()
        ]);
    }

    public static function update(Request $request, $id)
    {
        $validate = PersonValidator::validate($request->getPostVars());
        if(!$validate) {
            $entityManager = EntityManagerFactory::getEntityManager();
            $personRepository = $entityManager->getRepository(Person::class);
            $person = $personRepository->find($id);
            if ($person) {
                $person->setName($request->getPostVars()['name']);
                $person->setCpf($request->getPostVars()['cpf']);
                try {
                    $entityManager->flush();
                    Router::redirect('/pessoas');
                    return self::index();
                } catch (\Exception $e) {
                    return View::render('error', ['msg' => 'Não foi possível atualizar o recurso informado.']);
                }
            }
            return View::render('error', ['msg' => 'Não foi possível encontrar o recurso informado.']);
        }
        return View::render('error', ['msg' => 'Erros de validação foram encontrados: '.$validate]);
    }

    public static function destroy($id)
    {
        $entityManager = EntityManagerFactory::getEntityManager();
        $personRepository = $entityManager->getRepository(Person::class);
        $person = $personRepository->find($id);
        if($person) {
            $entityManager->remove($person);
            try {
                $entityManager->flush();
                Router::redirect('/pessoas');
            } catch (\Exception $e) {
                return View::render('error', ['msg' => 'Não foi possível deletar o recurso informado.']);
            }
        }
        return View::render('error', ['msg' => 'Não foi possível encontrar o recurso informado.']);
    }

    private static function getRepository()
    {
        $entityManager = EntityManagerFactory::getEntityManager();
        $personRepository = $entityManager->getRepository(Person::class);
        return $personRepository;
    }
}