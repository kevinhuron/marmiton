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
    /** get last recette for the index
     * @return \Connector\PDOStatement
     */
    public function get_recette_index()
    {
        $list_recette_index = Connector::prepare("SELECT * FROM recette INNER JOIN img ON recette.id_r = img.recetteid_r GROUP BY recette.id_r ORDER BY recette.id_r DESC LIMIT 9");
        return $list_recette_index;
    }

    /** get all recette for the list
     * @return \Connector\PDOStatement
     */
    public function get_all_recette_list()
    {
        $list_recette = Connector::prepare("SELECT * FROM recette INNER JOIN img ON recette.id_r = img.recetteid_r GROUP BY recette.id_r ORDER BY recette.id_r DESC");
        return $list_recette;
    }

    /** get recette by user
     * @param $login
     * @return \Connector\PDOStatement
     */
    public function get_recipients_user($login)
    {
        $list_recette = Connector::prepare("SELECT * FROM (marmiton.recette INNER JOIN marmiton.user on marmiton.user.id_u = marmiton.recette.userid_u) INNER JOIN marmiton.img ON marmiton.recette.id_r =  marmiton.img.recetteid_r WHERE user.login = ? GROUP BY recette.id_r  ORDER BY recette.id_r DESC;", array($login));
        return $list_recette;
    }

    /** get recette for comparaison between titles
     * @param $recette_title
     * @return \Connector\PDOStatement
     */
    public function get_all_recette_title($recette_title)
    {
        $list_title_recette = Connector::prepare("SELECT * FROM recette INNER JOIN img ON recette.id_r = img.recetteid_r WHERE recette.title LIKE '%$recette_title%' GROUP BY recette.id_r");
        return $list_title_recette;
    }

    /** get info of one recette
     * @param $id_r
     * @return \Connector\PDOStatement
     */
    public function get_content_recette($id_r)
    {
        $list_title_recette = Connector::prepare("SELECT * FROM recette INNER JOIN img ON recette.id_r = img.recetteid_r WHERE recette.id_r = $id_r");
        return $list_title_recette;
    }

    /** get ingredients by recette
     * @param $id_r
     * @return \Connector\PDOStatement
     */
    public function get_ingredients($id_r)
    {
        $list_ingre = Connector::prepare("SELECT * FROM ingredient INNER JOIN recette ON recette.id_r = ingredient.recetteid_r WHERE recette.id_r = $id_r");
        return $list_ingre;
    }

    /** get step by recette
     * @param $id_r
     * @return \Connector\PDOStatement
     */
    public function get_step($id_r)
    {
        $list_step = Connector::prepare("SELECT * FROM step INNER JOIN recette ON recette.id_r = step.recetteid_r WHERE recette.id_r = $id_r");
        return $list_step;
    }

    /** get score by recette
     * @param $id_r
     * @return \Connector\PDOStatement
     */
    public function get_score($id_r)
    {
        $list_score = Connector::prepare("SELECT * FROM score INNER JOIN recette ON recette.id_r = score.recetteid_r WHERE recette.id_r = $id_r");
        return $list_score;
    }

    /** get categ by recette
     * @param $id_r
     * @return \Connector\PDOStatement
     */
    public function get_categ($id_r)
    {
        $list_score = Connector::prepare("SELECT * FROM recette JOIN category_recette ON category_recette.recetteid_r = recette.id_r JOIN category ON category_recette.categoryid_c = category.id_c WHERE recette.id_r = $id_r");
        return $list_score;
    }

    /** get all categ
     * @return \Connector\PDOStatement
     */
    public function get_list_categ()
    {
        $list_categ = Connector::prepare("SELECT * FROM category");
        return $list_categ;
    }

    /** add a new recette in recette table
     * @param $title
     * @param $type_dish
     * @param $vege
     * @param $diff
     * @param $cost
     * @param $tmp_cook
     * @param $tmp_prep
     * @param $nb_port
     * @param $drink
     * @param $note
     * @param $id_u
     * @return \Connector\PDOStatement|string
     */
    public function add_recette($title,$type_dish,$vege,$diff,$cost,$tmp_cook,$tmp_prep,$nb_port,$drink,$note,$id_u)
    {
        try {
            $result = Connector::prepare("INSERT INTO recette(title,type_dish,vegetarian,difficulty,cost,cook_time,time_prep,nb_port,drink,note,userid_u) VALUES(?,?,?,?,?,?,?,?,?,?,?)", array($title,$type_dish,$vege,$diff,$cost,$tmp_cook,$tmp_prep,$nb_port,$drink,$note,$id_u));
            return $result;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** get last recette
     * @return \Connector\PDOStatement
     */
    public function get_last_id_rec()
    {
        $last_id = Connector::prepare("SELECT * FROM recette ORDER BY recette.id_r DESC LIMIT 1");
        return $last_id;
    }

    /** get last categ
     * @return \Connector\PDOStatement
     */
    public function get_last_id_categ()
    {
        $last_id_r = Connector::prepare("SELECT * FROM category ORDER BY category.id_c DESC LIMIT 1");
        return $last_id_r;
    }

    /** inert into category_recette table
     * @param $last_c
     * @param $last_r
     * @return \Connector\PDOStatement|string
     */
    public function insert_lien_c_r($last_c, $last_r)
    {
        try {
            $res = Connector::prepare("INSERT INTO category_recette(categoryid_c,recetteid_r) VALUES(?,?)", array($last_c, $last_r));
            return $res;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** insert in category and check if categ exit
     * @param $categ
     * @return \Connector\PDOStatement|string
     */
    public function add_categ($categ)
    {
        if ($this->check_if_categ_exist($categ) == 1)
        {
            $last_r = $this->get_last_id_rec()->fetchAll();
            $last_c = $this->get_last_id_categ()->fetchAll();
            $this->insert_lien_c_r($last_c,$last_r);
        }
        else{
            try {
                $result = Connector::prepare("INSERT INTO category(name_c) VALUES(?)", array($categ));
                $last_r = $this->get_last_id_rec()->fetchAll();
                $last_c = $this->get_last_id_categ()->fetchAll();
                $this->insert_lien_c_r($last_c,$last_r);
                return $result;
            } catch (PDOException $e) {
                return ($e->getMessage());
            }
        }
    }

    /** check if categ exist
     * @param $categ
     * @return int
     */
    public function check_if_categ_exist($categ)
    {
        $categ_list = $this->get_list_categ()->fetchAll();
        foreach($categ_list as $oneCateg){
            if($oneCateg['name_c'] == $categ)
            {
                return 1;
            }
        }
        return 0;
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