<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\KingdomManager;
use App\Model\PersonManager;

/**
 * Class PersonController
 *
 */
class PersonController extends AbstractController
{


    /**
     * Display person listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $personManager = new PersonManager();
        $persons = $personManager->selectAll();

        return $this->twig->render('Person/index.html.twig', ['persons' => $persons]);
    }


    /**
     * Display person informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $personManager = new PersonManager();
        $person = $personManager->selectOneWithKingdom($id);

        return $this->twig->render('Person/show.html.twig', ['person' => $person]);
    }

    private function validation ()
    {
//        $validator = new Validator();
//        $validator->validate($person['firstname'], 'prénom', [
//            new NotEmpty(),
//            new Lenth(['min'=>20, 'max'=>80])
//        ]);
//        $validator->validate($person['lastname'], 'nom', [
//            new NotEmpty(),
//            new Lenth(['min'=>10, 'max'=>80])
//        ]);
//
//        $errors = $validator->getErrors();


        if (empty($person['firstname'])) {
            $errors['firstname'][] = 'Le prénom ne doit pas etre vide';
        }
        if (strlen($person['firstname'])>80) {
            $errors['firstname'][] = 'Le prénom est trop long';
        }

        return $errors ?? [];
    }

    /**
     * Display person edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $kingdomManager = new KingdomManager();
        $kingdoms = $kingdomManager->selectAll();

        $personManager = new PersonManager();
        $person = $personManager->selectOneById($id);

        $errors=[];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $person = array_map('trim', $_POST);
            $error = $this->validation();
            if (empty($errors)) {
                $personManager->update($person);
                header('Location: /person/show/'. $person['id']);
            }
        }

        return $this->twig->render('Person/edit.html.twig', [
            'person' => $person,
            'kingdoms'=>$kingdoms,
            'errors' => $errors,
        ]);
    }


    /**
     * Display person creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {
        $kingdomManager = new KingdomManager();
        $kingdoms = $kingdomManager->selectAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $personManager = new PersonManager();
            $person = [
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'birthday' => $_POST['birthday'],
                'kingdom_id' => $_POST['kingdom_id'],
            ];
            $id = $personManager->insert($person);
            header('Location:/person/show/' . $id);
        }


        return $this->twig->render('Person/add.html.twig', ['kingdoms' => $kingdoms]);
    }


    /**
     * Handle person deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $personManager = new PersonManager();
        $personManager->delete($id);
        header('Location:/person/index');
    }
}
