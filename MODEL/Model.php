<?php

/**
 * Created by PhpStorm.
 * User: kevinhuron
 * Date: 18/12/2015
 * Time: 09:50
 */

namespace DataLayer;
use Connector\Connector;
use Controller\Controller;

class Model
{
    public function get_recette_index()
    {
        $list_recette_index = Connector::prepare("SELECT * FROM recette INNER JOIN img ON recette.id_r = img.recetteid_r GROUP BY recette.id_r ORDER BY recette.id_r DESC LIMIT 9");
        return $list_recette_index;
    }

    public function get_all_recette_list()
    {
        $list_recette = Connector::prepare("SELECT * FROM recette INNER JOIN img ON recette.id_r = img.recetteid_r GROUP BY recette.id_r ORDER BY recette.id_r DESC");
        return $list_recette;
    }

    public function get_recipients_user($login)
    {
        $list_recette = Connector::prepare("SELECT * FROM (marmiton.recette INNER JOIN marmiton.user on marmiton.user.id_u = marmiton.recette.userid_u) INNER JOIN marmiton.img ON marmiton.recette.id_r =  marmiton.img.recetteid_r WHERE user.login = ? GROUP BY recette.id_r  ORDER BY recette.id_r DESC;", array($login));
        return $list_recette;
    }

    public function get_all_recette_title($recette_title)
    {
        $list_title_recette = Connector::prepare("SELECT * FROM recette INNER JOIN img ON recette.id_r = img.recetteid_r WHERE recette.title LIKE '%$recette_title%' GROUP BY recette.id_r");
        return $list_title_recette;
    }

    public function get_content_recette($id_r)
    {
        $list_title_recette = Connector::prepare("SELECT * FROM recette INNER JOIN img ON recette.id_r = img.recetteid_r WHERE recette.id_r = $id_r");
        return $list_title_recette;
    }

    public function get_ingredients($id_r)
    {
        $list_ingre = Connector::prepare("SELECT * FROM ingredient INNER JOIN recette ON recette.id_r = ingredient.recetteid_r WHERE recette.id_r = $id_r");
        return $list_ingre;
    }

    public function get_step($id_r)
    {
        $list_step = Connector::prepare("SELECT * FROM step INNER JOIN recette ON recette.id_r = step.recetteid_r WHERE recette.id_r = $id_r");
        return $list_step;
    }

    public function get_score($id_r)
    {
        $list_score = Connector::prepare("SELECT * FROM score INNER JOIN recette ON recette.id_r = score.recetteid_r WHERE recette.id_r = $id_r");
        return $list_score;
    }

    public function get_categ($id_r)
    {
        $list_score = Connector::prepare("SELECT * FROM recette JOIN category_recette ON category_recette.recetteid_r = recette.id_r JOIN category ON category_recette.categoryid_c = category.id_c WHERE recette.id_r = $id_r");
        return $list_score;
    }

    /** make the connection at login
     * @param $login User login
     * @param $password User password
     * @return PDOStatement
     */
    public function make_login_connector($login, $password)
    {
        $result = Connector::prepare("select * from user where login = ? and mdp = ?", array($login, $password));
        return $result;
    }
}