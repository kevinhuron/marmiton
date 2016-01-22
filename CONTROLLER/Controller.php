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

    /** check if user is login
     * @return int
     */
    private function check_login()
    {
        return (isset($_SESSION['login_marmiton']) && isset($_SESSION['first_name_marmiton']))? 1 : 0;
    }

    /**
     * LOGIN PAGE
     */
    public function login_page()
    {
        echo $_SESSION['twig']->render("login.html.twig");
    }

    /**
     * Get index recipients
     * @echo JSON Object index recipients
     */
    public function get_index_recipients()
    {
        $model = $this->getModel();
        $recettes_index = $model->get_recette_index()->fetchAll();
        unset($model);
        echo json_encode(array("receipts"=>$recettes_index,"name"=>(isset($_SESSION['first_name_marmiton']))? $_SESSION['first_name_marmiton']: NULL));
    }

    /**
     * Get index recipients
     * @echo JSON Object index recipients
     */
    public function get_user_co()
    {
        echo json_encode(array("name"=>(isset($_SESSION['first_name_marmiton']))? $_SESSION['first_name_marmiton']: NULL));
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
        echo json_encode(array("all_receipts"=>$recettes_list, "name"=>(isset($_SESSION['first_name_marmiton']))? $_SESSION['first_name_marmiton']: NULL));
    }

    /** get recipients by user
     *
     */
    public function get_recipients_user()
    {
        if ($this->check_login() == 1) {
            $model = $this->getModel();
            $recettes_list = $model->get_recipients_user($_SESSION['login_marmiton'])->fetchAll();
            unset($model);
            echo json_encode(array("all_receipts" => $recettes_list));
        }
        else
            echo $_SESSION['twig']->render("login.html.twig", array("onlocation"=>"dashboardShow"));
    }

    /** show the new recette name form page
     *
     */
    public function new_title_recette()
    {
        echo $_SESSION['twig']->render(($this->check_login() == 1)? "new_recette_name.html.twig" : "login.html.twig", array("onlocation"=>"new_recette"));
    }

    /** show the dashboard page
     *
     */
    public function dashboard_show()
    {
        echo $_SESSION['twig']->render(($this->check_login() == 1)? "dashboard.html.twig" : "login.html.twig", array("onlocation"=>"dashboardShow"));
    }

    /** get all title recette
     * @param $recette_title
     */
    public function get_title_recette($recette_title)
    {
        $model = $this->getModel();
        $recette_list_title = $model->get_all_recette_title($recette_title)->fetchAll();
        unset($model);
        echo json_encode(array("all_title_recette"=>$recette_list_title, "name"=>(isset($_SESSION['first_name_marmiton']))? $_SESSION['first_name_marmiton']: NULL));
    }

    /** show the form new recette page
     * @param $recette_title
     */
    public function new_recette($recette_title)
    {
        $model = $this->getModel();
        $list_categ = $model->get_list_categ()->fetchAll();
        unset($model);
        echo $_SESSION['twig']->render("new_recette.html.twig", array('title'=>$recette_title, 'categ'=>$list_categ, 'idu'=>$_SESSION['idu']));
    }

    /** show the content recette page
     * @param $id_r
     */
    public function show_content($id_r)
    {
        echo $_SESSION['twig']->render("show_content_recette.html.twig", array('id_r'=>$id_r));
    }

    /** get the content of a recette
     * @param $id_r
     */
    public function get_content_recette($id_r)
    {
        $model = $this->getModel();
        $list_recette = $model->get_content_recette($id_r)->fetchAll();
        $list_ingre = $model->get_ingredients($id_r)->fetchAll();
        $list_step = $model->get_step($id_r)->fetchAll();
        $list_score = $model->get_score($id_r)->fetchAll();
        $list_categ = $model->get_categ($id_r)->fetchAll();
        unset($model);
        echo json_encode(array("list_rec"=>$list_recette,
            "list_ingre"=>$list_ingre,
            "list_step"=>$list_step,
            "list_score"=>$list_score,
            "list_categ"=>$list_categ,
            "name"=>(isset($_SESSION['first_name_marmiton']))? $_SESSION['first_name_marmiton']: NULL));
    }

    /** insert a new recette in recette table
     * @param $request
     */
    public function insert_recette($request)
    {
        $model = $this->getModel();
        $result_r = $model->add_recette($request['title'],$request['type_dish'],$request['vege'],$request['diff'],$request['cost'],$request['tmp_cook'],$request['tmp_prep'],$request['nb_port'],$request['drink'],$request['note'],$request['user']);
        if ($result_r->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result_r);
        unset($model);
    }

    /** insert new categ in categ table and categ_recette link table
     * @param $request
     */
    public function insert_categ($request)
    {
        $model = $this->getModel();
        $result_c = $model->add_categ($request['categ']);
        if ($result_c->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result_c);
        unset($model);
    }

    /** show form for insert step
     *
     */
    public function form_step()
    {
        echo $_SESSION['twig']->render("form_step.html.twig");
    }

    /** insert step
     *
     */
    public function insert_step($step)
    {
        $model = $this->getModel();
        $result = $model->add_step($step);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** show form for insert ingredients
     *
     */
    public function form_ingredient()
    {
        echo $_SESSION['twig']->render("form_ingredients.html.twig");
    }

    /** insert ingredients
     *
     */
    public function insert_ingredient($ingre, $qte)
    {
        $model = $this->getModel();
        $result = $model->add_ingre($ingre, $qte);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** show form for insert img
     *
     */
    public function form_img_recette()
    {
        echo $_SESSION['twig']->render("form_img.html.twig");
    }

    public function import_img()
    {
        $upload_dir = "PUBLIC/IMG";
        $tmp_name = $_FILES['input_1']["tmp_name"];
        $name = $_FILES['input_1']["name"];
        $result = move_uploaded_file($tmp_name,$upload_dir/$name);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
    }

    /** make the login
     * @param $login
     * @param $password
     * @return int
     */
    static public function make_login($login, $password)
    {
        $_SESSION['login_marmiton'] = $login;
        $model = new Model();
        $rs = $model->make_login_connector($login, $password);
        $a = $rs->fetch();
        if ($a != NULL)
        {
            $_SESSION['idu'] = $a['id_u'];
            $_SESSION['first_name_marmiton'] = $a["first_name"];
            return $a["first_name"];
        }
        else
            return 0;
    }

    /** LOGOUT
     *
     */
    public function logout()
    {
        unset($_SESSION['login_marmiton']);
        unset($_SESSION['first_name_marmiton']);
        echo $_SESSION['twig']->render("index.html.twig");
    }

    /** return error
     * @param $pdo
     */
    private function return_error($pdo)
    {
        $error = $pdo->errorInfo();
        $error = $error[0] . " " . $error[1] . "  " . $error[2];
        echo $error;
    }
}