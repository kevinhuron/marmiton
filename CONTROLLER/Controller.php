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
///ADDED///
use RecursiveDirectoryIterator,RecursiveIteratorIterator;

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

    public function inscription_page()
    {
        echo $_SESSION['twig']->render("form_inscr.html.twig");
    }

    public function check_id_exist($login)
    {
        $model = $this->getModel();
        $result = $model->check_id_exist($login);
        $count = $result->rowCount();
        echo $count;
        unset($model);
    }

    public function inscription_user($id, $passwd, $last_name, $first_name, $addr, $cp, $ville, $birth)
    {
        $model = $this->getModel();
        $result_c = $model->inscription_user($id, $passwd, $last_name, $first_name, $addr, $cp, $ville, $birth);
        if ($result_c->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result_c);
        unset($model);
    }

    public function get_distinct_ingre_categ()
    {
        $model = $this->getModel();
        $distinct_ingre = $model->get_distinct_ingre()->fetchAll();
        $categ  = $model->get_list_categ()->fetchAll();
        unset($model);
        echo json_encode(array("distinct_ingre"=>$distinct_ingre,"categ"=>$categ));
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

    /** get the content of a recette to edit
     * @param $id_r
     */
    public function get_content_recette_edit($id_r)
    {
        $model = $this->getModel();
        $list_recette = $model->get_content_recette_edit($id_r)->fetchAll();
        $list_ingre = $model->get_ingredients_edit($id_r)->fetchAll();
        $list_step = $model->get_step_edit($id_r)->fetchAll();
        $list_score = $model->get_score_edit($id_r)->fetchAll();
        $list_categ = $model->get_categ_edit($id_r)->fetchAll();
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
    public function form_step($idr,$title)
    {
        echo $_SESSION['twig']->render("form_step.html.twig", array('idr'=>$idr,'title'=>$title));
    }

    /** insert step
     *
     */
    public function insert_step($step,$idr)
    {
        $model = $this->getModel();
        $result = $model->add_step($step,$idr);
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
        $model = $this->getModel();
        $last_id_r = $model->get_last_id_rec()->fetch();
        echo $_SESSION['twig']->render("form_ingredients.html.twig", array('last_idr'=>$last_id_r['id_r'],'title'=>$last_id_r['title']));
    }

    /** insert ingredients
     *
     */
    public function insert_ingredient($ingre, $qte,$idr)
    {
        $model = $this->getModel();
        $result = $model->add_ingre($ingre, $qte, $idr);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** show form for insert img
     *
     */
    public function form_img_recette($idr)
    {
        echo $_SESSION['twig']->render("form_img.html.twig", array('idr'=>$idr));
    }

    /** import img
     * @param $file
     * @param $idr
     */
    public function import_img($file, $idr)
    {
        $import_img = function($file, $idr) {
            $upload_dir = "PUBLIC/IMG/RECIPE/$idr";
            if (!is_dir($upload_dir))
                mkdir($upload_dir);
            if (file_exists($upload_dir.'/'.$file['input_1']['name']))
                return 0;
            return (move_uploaded_file($file['input_1']['tmp_name'], $upload_dir.'/'.$file['input_1']['name']))? 1 : 0;
        };

        $import_sql = function($file, $idr){
            $upload_file = "RECIPE/$idr/".$file['input_1']['name'];
            $model = $this->getModel();
            $result = $model->import_img($upload_file, $idr);
            unset($model);
            return ($result->errorInfo()[1] == NULL)? 1 : 0;
        };

        echo ($import_img($file, $idr) && $import_sql($file, $idr))? 1 : 0;
    }

    /** delete recette
     * @param $idr
     */
    public function del_recipient_usr($idr)
    {
        $model = $this->getModel();
        $result = $model->del_recipient($idr);
        $this->remove_directory("PUBLIC/IMG/RECIPE/$idr");
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** delete recette
     * @param $idr
     */
    public function del_recipient_usr_verif($idr)
    {
        $model = $this->getModel();
        $result = $model->del_recipient($idr);
        $this->remove_directory("PUBLIC/IMG/RECIPE/$idr");
        unset($model);
    }

    /** Verify matches between recipes
     * @param $id recipe id
     */
    public function verify_matches($id)
    {
        $matches = 0;
        $model = $this->getModel();
        $result = $model->get_content_recette_new($id)->fetch();
        $name = $result['title'];
        $c_recipe = array_map('strtoupper', $result);
        $table =  $model->get_all_recette_title_new($name)->fetchAll();
        foreach ($table as $one)
        {
            $ma = array_map('strtoupper', $one);
            if ($id != $ma["id_r"]) {
                // recherche des similitudes
                $similar = array_intersect($c_recipe, $ma);
                $matches = 2 * count($similar) / (count($c_recipe) + count($ma));
                if ($matches >= 0.8) {
                    $this->del_recipient_usr_verif($id);
                    break;
                }
            }
        }
        echo ($matches * 100);
    }

    /** remove directory or one img
     * @param $path
     * @return bool
     */
    public function remove_directory($path)
    {
        if (is_dir($path) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $file) {
                if (in_array($file->getBasename(), array('.', '..')) !== true) {
                    if ($file->isDir() === true)
                        rmdir($file->getPathName());
                    else if (($file->isFile() === true) || ($file->isLink() === true))
                        unlink($file->getPathname());
                }
            }
            return rmdir($path);
        }
        else if ((is_file($path) === true) || (is_link($path) === true))
            return unlink($path);
        return false;
    }

    /** edit recette page
     *
     */
    public function edit_recette_page($idr)
    {
        $model = $this->getModel();
        $list_categ = $model->get_list_categ()->fetchAll();
        echo $_SESSION['twig']->render("edit_rec.html.twig",array('id_r'=>$idr,'categ'=>$list_categ));
    }

    /** update title from update page
     * @param $idr
     * @param $value
     */
    public function update_title($idr, $value)
    {
        $model = $this->getModel();
        $result = $model->update_title($idr, $value);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** update type dish from update page
     * @param $idr
     * @param $value
     */
    public function update_type_dish($idr, $value)
    {
        $model = $this->getModel();
        $result = $model->update_type_dish($idr, $value);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** update difficulty from update page
     * @param $idr
     * @param $value
     */
    public function update_diff($idr, $value)
    {
        $model = $this->getModel();
        $result = $model->update_diff($idr, $value);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** update cost from update page
     * @param $idr
     * @param $value
     */
    public function update_cost($idr, $value)
    {
        $model = $this->getModel();
        $result = $model->update_cost($idr, $value);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** update tmp prep from update page
     * @param $idr
     * @param $value
     */
    public function update_tmp_prep($idr, $value)
    {
        $model = $this->getModel();
        $result = $model->update_tmp_prep($idr, $value);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** update tmp cook from update page
     * @param $idr
     * @param $value
     */
    public function update_tmp_cook($idr, $value)
    {
        $model = $this->getModel();
        $result = $model->update_tmp_cook($idr, $value);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** update nb port from update page
     * @param $idr
     * @param $value
     */
    public function update_nb_port($idr, $value)
    {
        $model = $this->getModel();
        $result = $model->update_nb_port($idr, $value);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** update drink from update page
     * @param $idr
     * @param $value
     */
    public function update_drink($idr, $value)
    {
        $model = $this->getModel();
        $result = $model->update_drink($idr, $value);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** update note (remarque) from update page
     * @param $idr
     * @param $value
     */
    public function update_note($idr, $value)
    {
        $model = $this->getModel();
        $result = $model->update_note($idr, $value);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** update vegetarian from update page
     * @param $idr
     * @param $value
     */
    public function update_vege($idr, $value)
    {
        $model = $this->getModel();
        $result = $model->update_vege($idr, $value);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** delete one ingre from update page
     * @param $idr
     * @param $id_in
     */
    public function del_ingre($idr, $id_in)
    {
        $model = $this->getModel();
        $result = $model->del_ingre($idr, $id_in);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** delete one step from update page
     * @param $idr
     * @param $id_step
     */
    public function del_step($idr, $id_step)
    {
        $model = $this->getModel();
        $result = $model->del_step($idr, $id_step);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** delete one categ from update page
     * @param $idr
     * @param $id_c
     */
    public function del_categ_from_rec($idr, $id_c)
    {
        $model = $this->getModel();
        $result = $model->del_categ_from_rec($idr, $id_c);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** DELETE one img from update page
     * @param $request
     */
    public function del_img_from_rec($request)
    {
        $model = $this->getModel();
        $result = $model->del_img_from_rec_sql($request['idr'], $request['id_img']);
        $this->remove_directory("PUBLIC/IMG/".$request['filename']);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
    }

    /** form update img
     *
     */
    public function form_update_img($idr)
    {
        echo $_SESSION['twig']->render("import_img_edit.html.twig", array('idr'=>$idr));
    }

    /** kitchen mode page
     * @param $idr
     */
    public function kitchen_mode_page($idr)
    {
        echo $_SESSION['twig']->render("kitchen_mode.html.twig", array('idr'=>$idr));
    }

    /** kitchen mode
     * @param $idr
     */
    public function kitchen_mode($idr)
    {
        $model = $this->getModel();
        $step = $model->get_step($idr)->fetchAll();
        $recette = $model->get_content_recette($idr)->fetchAll();
        unset($model);
        echo json_encode(array("step"=>$step,"recette"=>$recette));
    }

    /** search
     * @param $request
     */
    public function search($request)
    {
        $model = $this->getModel();
        $result = $model->search($request['searchText'], $request['dish'], $request['ingre'], $request['categ'], $request['cost'], $request['diff'])->fetchAll();
        unset($model);
        echo json_encode(array("result"=>$result));
    }

    /** set a score to a recette
     * @param $idr
     * @param $score
     */
    public function newScore($idr, $score)
    {
        $model = $this->getModel();
        $result = $model->newScore($idr,$score);
        if ($result->errorInfo()[1] == NULL)
            echo 1;
        else
            $this->return_error($result);
        unset($model);
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