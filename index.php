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
     * @throws DatabaseException
     * @throws TableException
     */
    static private function router($request)
    {
        if (isset($request["run"])) {
            $controller = new Controller();
            if ($request["run"] == "indexAction")
                $controller->indexAction();
            if ($request["run"] == "getAllReceipts")
                $controller->get_all_recipients();
            else
                echo $_SESSION['twig']->render("error.html.twig", array("error" => "Mauvais paramètres !"));
            unset($controller);
        }
        else if ($request == NULL)
            (new Controller())->indexAction();
        else
            echo $_SESSION['twig']->render("error.html.twig", array("error" => "Aucune action"));
    }
}