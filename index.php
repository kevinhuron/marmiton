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
    ///MODIFIED METHOD///
    /** MAIN FUNCTION
     * @param $request
     */
    static public function main($request)
    {
        require_once "Starter.php";
        new Starter();
        if ($request != NULL) {
            $controller = new Controller();
            self::router($request);
            unset($controller);
        }
        else
            (new Controller())->indexAction();
    }

    ///END MODIFIED///

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
            else if ($request["run"] == "importImg")
                $controller->import_img($_FILES, $request['idr']);
            else if ($request["run"] == "check_matches")
                $controller->verify_matches($request['id']);
            else if ($request["run"] == "delRecipientsUser")
                $controller->del_recipient_usr($request['idr']);
            else if ($request["run"] == "edit_rec_page")
                $controller->edit_recette_page($request['idr']);
            else if ($request["run"] == "recette_edited")
                $controller->get_content_recette_edit($request['idr']);
            else if ($request["run"] == "update_title")
                $controller->update_title($request['idr'],$request['value']);
            else if ($request["run"] == "update_type_dish")
                $controller->update_type_dish($request['idr'],$request['value']);
            else if ($request["run"] == "update_diff")
                $controller->update_diff($request['idr'],$request['value']);
            else if ($request["run"] == "update_cost")
                $controller->update_cost($request['idr'],$request['value']);
            else if ($request["run"] == "update_tmp_prep")
                $controller->update_tmp_prep($request['idr'],$request['value']);
            else if ($request["run"] == "update_tmp_cook")
                $controller->update_tmp_cook($request['idr'],$request['value']);
            else if ($request["run"] == "update_nb_port")
                $controller->update_nb_port($request['idr'],$request['value']);
            else if ($request["run"] == "update_drink")
                $controller->update_drink($request['idr'],$request['value']);
            else if ($request["run"] == "update_note")
                $controller->update_note($request['idr'],$request['value']);
            else if ($request["run"] == "update_vege")
                $controller->update_vege($request['idr'],$request['value']);
            else if ($request["run"] == "del_ingre")
                $controller->del_ingre($request['idr'],$request['id_in']);
            else if ($request["run"] == "del_step")
                $controller->del_step($request['idr'],$request['id_step']);
            else if ($request["run"] == "del_categ_from_rec")
                $controller->del_categ_from_rec($request['idr'],$request['id_c']);
            else if ($request["run"] == "add_categ_from_update")
                $controller->insert_categ($request);
            else if ($request["run"] == "del_img_from_rec")
                $controller->del_img_from_rec($request);
            else if ($request["run"] == "form_update_img")
                $controller->form_update_img($request['idr']);
            else if ($request["run"] == "newScore")
                $controller->newScore($request['idr'], $request['score']);
            else if ($request["run"] == "kitchenMode")
                $controller->kitchen_mode_page($request['idr']);
            else if ($request["run"] == "get_kitchen_mode")
                $controller->kitchen_mode($request['idr']);
            else if ($request["run"] == "inscription")
                $controller->inscription_page();
            else if ($request["run"] == "check_id_exist")
                $controller->check_id_exist($request['id']);
            else if ($request["run"] == "inscription_user")
                $controller->inscription_user($request['id'],$request['passwd'],$request['last_name'],$request['first_name'],$request['addr'],$request['cp'],$request['ville'],$request['birth']);
            else if ($request["run"] == "get_distinct_ingre_categ")
                $controller->get_distinct_ingre_categ();
            else if ($request["run"] == "search")
                $controller->search($request);
            else
                echo $_SESSION['twig']->render("error.html.twig", array("error" => "Mauvais paramètres !"));
            unset($controller);
        }
        else
            echo $_SESSION['twig']->render("error.html.twig", array("error" => "Aucune action"));
    }
}