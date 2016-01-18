<?php
/**
 * Created by PhpStorm.
 * User: kevinhuron
 * Date: 18/12/2015
 * Time: 14:16
 */

session_start();
$request = NULL;
if (isset($_REQUEST)) {
    $request = $_REQUEST;
}

Index::main($request);

use Controller\Controller;
class Index
{
    /** MAIN FUNCTION
     * @param $request
     */
    static public function main($request)
    {
        require_once "Starter.php";
        new Starter();
        $controller = new Controller();
            self::router($request);
        unset($controller);
    }

    /** ROUTER
     * @param $request
     */
    static private function router($request)
    {
        if (isset($request["run"])) {
            $controller = new Controller();
            if ($request["run"] == "indexAction")
                $controller->indexAction();
            else if ($request["run"] == "show_recettes")
                $controller->list_recette();
            else if ($request["run"] == "getAllReceipts")
                $controller->get_ALL_recipients();
            else if ($request["run"] == "getIndexReceipts")
                $controller->get_index_recipients();
            else if ($request["run"] == "new_recette")
                $controller->new_title_recette();
            else if ($request["run"] == "getTitleRecette")
                $controller->get_title_recette($request['value']);
            else if ($request["run"] == "create_recette")
                $controller->new_recette();
            else
                echo $_SESSION['twig']->render("error.html.twig", array("error" => "Mauvais paramètres !"));
            unset($controller);
        }/*
        else if (isset($request))
            (new Controller())->indexAction();*/
        else
            echo $_SESSION['twig']->render("error.html.twig", array("error" => "Aucune action"));
    }
}