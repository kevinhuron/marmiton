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

    /** when user is login
     * @param $request
     */
    static private function on_login($request)
    {
        echo Controller::make_login($request['id'], $request['passwd']);
    }

    /** check if user is login
     * @return int
     */
    static private function check_login()
    {
        return (isset($_SESSION['login']) && isset($_SESSION['passwd']))? 1 : 0;
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
                $controller->new_recette($request['value']);
            else if ($request["run"] == "show_content")
                $controller->show_content($request['cle']);
            else if ($request["run"] == "get_content")
                $controller->get_content_recette($request['cle']);
            else if ($request["run"] == "login")
                $controller->login_page();
            else if ($request["run"] == "logout")
                $controller->logout();
            else if ($request["run"] == "getRecipientsUser")
                $controller->get_recipients_user();
            else if ($request["run"] == "makeLogin")
                self::on_login($request);
            else if ($request["run"] == "getUserCo")
                $controller->get_user_co();
            else if ($request["run"] == "dashboardShow")
                $controller->dashboard_show();
            else if ($request["run"] == "insertNewRecette")
            {
                $controller->insert_recette($request);
                $controller->insert_categ($request);
            }
            else if ($request["run"] == "newRecetteImg")
                $controller->form_img_recette($request['idr']);
            else if ($request["run"] == "formIngre")
                $controller->form_ingredient();
            else if ($request["run"] == "form_step")
                $controller->form_step($request['idr'],$request['title']);
            else if ($request["run"] == "insertIngre")
                $controller->insert_ingredient($request['ingred'],$request['qte'],$request['idr']);
            else if ($request["run"] == "insertStep")
                $controller->insert_step($request['step'],$request['idr']);
            else if ($request["run"] == "insertImg")
                $controller->import_img();
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