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

/**
 * Class KingdomController
 *
 */
class KingdomController extends AbstractController
{


    /**
     * Display kingdom listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $kingdomManager = new KingdomManager();
        $kingdoms = $kingdomManager->selectAll();

        return $this->twig->render('Kingdom/index.html.twig', ['kingdoms' => $kingdoms]);
    }


    /**
     * Display kingdom informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $kingdomManager = new KingdomManager();
        $kingdom = $kingdomManager->selectOneById($id);

        return $this->twig->render('Kingdom/show.html.twig', ['kingdom' => $kingdom]);
    }


    /**
     * Display kingdom edition page specified by $id
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
        $kingdom = $kingdomManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kingdom['name'] = $_POST['name'];
            $kingdomManager->update($kingdom);
        }

        return $this->twig->render('Kingdom/edit.html.twig', ['kingdom' => $kingdom]);
    }


    /**
     * Display kingdom creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kingdomManager = new KingdomManager();
            $kingdom = [
                'name' => $_POST['name'],
            ];
            $id = $kingdomManager->insert($kingdom);
            header('Location:/kingdom/show/' . $id);
        }

        return $this->twig->render('Kingdom/add.html.twig');
    }


    /**
     * Handle kingdom deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $kingdomManager = new KingdomManager();
        $kingdomManager->delete($id);
        header('Location:/kingdom/index');
    }
}
