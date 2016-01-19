<?php

/**
 * Created by PhpStorm.
 * User: kevinhuron
 * Date: 18/12/2015
 * Time: 09:46
 */

namespace Controller;
use AbsSupervisor\AbstractController;
use DataLayer\Model;
use PDO;

class Controller extends AbstractController
{

    /**
     * First Page of App
     */
    public function indexAction()
    {
        echo $_SESSION['twig']->render("index.html.twig");
    }

    /**.
     * Get index recipients
     * @echo JSON Object index recipients
     */
    public function get_index_recipients()
    {
        $model = $this->getModel();
        $recettes_index = $model->get_recette_index()->fetchAll();
        unset($model);
        echo json_encode(array("receipts"=>$recettes_index));
    }

    /**
     * show list of recette
     */
    public function list_recette()
    {
        echo $_SESSION['twig']->render("show_list_recette.html.twig");
    }

    /**
     * Get ALL RECIPIENTS
     */
    public function get_ALL_recipients()
    {
        $model = $this->getModel();
        $recettes_list = $model->get_all_recette_list()->fetchAll();
        unset($model);
        echo json_encode(array("all_receipts"=>$recettes_list));
    }

    public function new_title_recette()
    {
        echo $_SESSION['twig']->render("new_recette_name.html.twig");
    }

    public function get_title_recette($recette_title)
    {
        $model = $this->getModel();
        $recette_list_title = $model->get_all_recette_title($recette_title)->fetchAll();
        unset($model);
        echo json_encode(array("all_title_recette"=>$recette_list_title));
    }


    public function new_recette($recette_title)
    {
        echo $_SESSION['twig']->render("new_recette.html.twig", array('title'=>$recette_title));
    }


    public function show_content($id_r)
    {
        echo $_SESSION['twig']->render("show_content_recette.html.twig", array('id_r'=>$id_r));
    }

    public function get_content_recette($id_r)
    {
        $model = $this->getModel();
        $recette_content = $model->get_content_recette($id_r)->fetchAll();
        unset($model);
        echo json_encode(array("content_recette"=>$recette_content));
    }













    /** make the login
     * @param $login
     * @param $password
     * @return int
     */
    static public function make_login($login, $password)
    {
        $_SESSION['login'] = $login;
        $_SESSION["passwd"] = $password;
        $model = new Model();
        $rs = $model->make_login_connector();
        if ($rs->errorInfo()[2] == NULL)
            return (1);
    }

    /** LOGOUT
     *
     */
    public function logout($arg)
    {
        unset($_SESSION['login']);
        unset($_SESSION["passwd"]);
        echo $_SESSION['twig']->render("login.html.twig", array("error" => ($arg == NULL) ? "Vous êtes dorénavant déconnecté" : "Aucune activité depuis 1440 secondes ou plus; veuillez vous reconnecter."));
    }
}