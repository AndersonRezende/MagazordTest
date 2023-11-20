<?php

namespace App\Controller;


use App\Entity\Contact;
use App\Entity\Person;
use App\Factory\EntityManagerFactory;
use App\Http\Request;
use App\Http\Router;
use App\Utils\View;
use App\Validator\ContactValidator;

class ContactController
{
    public static function index()
    {
        $contacts = self::getRepository()->findBy([], ['person' => 'ASC']);
        $item = '';
        foreach ($contacts as $contact) {
            $item .= View::renderPartial('contact/partial/table-item', [
               'id' => $contact->getId(),
               'type' => ucfirst($contact->getType()),
               'description' => $contact->getDescription(),
               'person' => $contact->getPerson()->getName()
            ]);
        }
        return View::render('contact/index', [
            'item' => $item
        ]);
    }

    public static function show($id)
    {
        $contact = self::getRepository()->find($id);
        if($contact) {
            $item = '';

            return View::render('contact/show', [
                'id' => $contact->getId(),
                'type' => ucfirst($contact->getType()),
                'description' => $contact->getDescription(),
                'person' => $contact->getPerson()->getName()
            ]);
        }
        return View::render('error', [
           'msg' => 'Não foi possível localizar o recurso informado.'
        ]);
    }

    public static function create()
    {
        $typeOptions = '';
        $options = [0 => 'Email', 1 => 'Telefone'];
        foreach ($options as $key => $text) {
            $typeOptions .= View::renderPartial('contact/partial/select-type', [
                'value' => $key,
                'text' => $text,
            ]);
        }

        $people = self::getRepository(Person::class)->findAll();
        $peopleOptions = '';
        foreach ($people as $person) {
            $peopleOptions .= View::renderPartial('contact/partial/select-type', [
                'value' => $person->getId(),
                'text' => $person->getName(),
            ]);
        }

        return View::render('contact/create', [
            'type' => $typeOptions,
            'people' => $peopleOptions
        ]);
    }

    public static function store(Request $request)
    {
        $validate = ContactValidator::validate($request->getPostVars());
        if(!$validate) {
            $entityManager = EntityManagerFactory::getEntityManager();
            $contact = new Contact();
            $person = $entityManager->getRepository(Person::class)->find($request->getPostVars()['person']);
            $contact->setType((bool) $request->getPostVars()['type']);
            $contact->setDescription($request->getPostVars()['description']);
            $contact->setPerson($person);
            try {
                $entityManager->persist($contact);
                $entityManager->flush();

                Router::redirect('/contatos');
            } catch (\Exception $e) {
                echo $e;
                return View::render('error', ['msg' => 'Não foi possível gravar o recurso informado.']);
            }
        }
        return View::render('error', ['msg' => 'Erros de validação foram encontrados: '.$validate]);
    }

    public static function edit($id)
    {
        $contact = self::getRepository()->find($id);
        if($contact) {
            $typeOptions = '';
            $options = [0 => 'Email', 1 => 'Telefone'];
            foreach ($options as $key => $text) {
                $typeOptions .= View::renderPartial('contact/partial/select-type', [
                    'value' => $key,
                    'text' => $text,
                    'selected' => $text == $contact->isType() ? 'selected' : ''
                ]);
            }

            $people = self::getRepository(Person::class)->findAll();
            $peopleOptions = '';
            foreach ($people as $person) {
                $peopleOptions .= View::renderPartial('contact/partial/select-type', [
                    'value' => $person->getId(),
                    'text' => $person->getName(),
                    'selected' => $person->getId() == $contact->getPerson()->getId() ? 'selected' : ''
                ]);
            }

            return View::render('contact/edit', [
                'id' => $contact->getId(),
                'type' => $typeOptions,
                'people' => $peopleOptions,
                'description' => $contact->getDescription()
            ]);
        }
        return View::render('error', ['msg' => 'Não foi possível deletar o recurso informado.']);
    }

    public static function update(Request $request, $id)
    {
        $validate = ContactValidator::validate($request->getPostVars());
        if(!$validate) {
            $entityManager = EntityManagerFactory::getEntityManager();
            $contactRepository = $entityManager->getRepository(contact::class);
            $contact = $contactRepository->find($id);
            if ($contact) {
                $person = $entityManager->getRepository(Person::class)->find($request->getPostVars()['person']);
                $contact->setType((bool) $request->getPostVars()['type']);
                $contact->setDescription($request->getPostVars()['description']);
                $contact->setPerson($person);
                try {
                    $entityManager->flush();
                    Router::redirect('/contatos');
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
        $contactRepository = $entityManager->getRepository(Contact::class);
        $contact = $contactRepository->find($id);
        if($contact) {
            $entityManager->remove($contact);
            try {
                $entityManager->flush();
                Router::redirect('/contatos');
            } catch (\Exception $e) {
                return View::render('error', ['msg' => 'Não foi possível deletar o recurso informado.']);
            }
        }
        return View::render('error', ['msg' => 'Não foi possível encontrar o recurso informado.']);
    }

    private static function getRepository($entityName = Contact::class)
    {
        $entityManager = EntityManagerFactory::getEntityManager();
        $contactRepository = $entityManager->getRepository($entityName);
        return $contactRepository;
    }
}