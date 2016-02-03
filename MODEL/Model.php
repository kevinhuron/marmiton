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
    /** New recipe image
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
        $list_recette = Connector::prepare("SELECT * FROM (marmiton.recette INNER JOIN marmiton.user on marmiton.user.id_u = marmiton.recette.userid_u) LEFT JOIN marmiton.img ON marmiton.recette.id_r =  marmiton.img.recetteid_r WHERE user.login = ? GROUP BY recette.id_r  ORDER BY recette.id_r DESC;", array($login));
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

    /** get info of one recette
     * @param $id_r
     * @return \Connector\PDOStatement
     */
    public function get_content_recette_edit($id_r)
    {
        $list_title_recette = Connector::prepare("SELECT * FROM recette LEFT JOIN img ON recette.id_r = img.recetteid_r WHERE recette.id_r = $id_r");
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

    public function get_distinct_ingre()
    {
        $list_ingre = Connector::prepare("SELECT DISTINCT(name_in) FROM ingredient");
        return $list_ingre;
    }

    /** get ingredients by recette
     * @param $id_r
     * @return \Connector\PDOStatement
     */
    public function get_ingredients_edit($id_r)
    {
        $list_ingre = Connector::prepare("SELECT * FROM ingredient RIGHT JOIN recette ON recette.id_r = ingredient.recetteid_r WHERE recette.id_r = $id_r");
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

    /** get step by recette
     * @param $id_r
     * @return \Connector\PDOStatement
     */
    public function get_step_edit($id_r)
    {
        $list_step = Connector::prepare("SELECT * FROM step RIGHT JOIN recette ON recette.id_r = step.recetteid_r WHERE recette.id_r = $id_r");
        return $list_step;
    }

    /** get score by recette
     * @param $id_r
     * @return \Connector\PDOStatement
     */
    public function get_score($id_r)
    {
        $list_score = Connector::prepare("SELECT id_score, round(AVG(value)) as score, recetteid_r FROM score INNER JOIN recette ON recette.id_r = score.recetteid_r WHERE recette.id_r = $id_r");
        return $list_score;
    }

    /** get score by recette
     * @param $id_r
     * @return \Connector\PDOStatement
     */
    public function get_score_edit($id_r)
    {
        $list_score = Connector::prepare("SELECT * FROM score RIGHT JOIN recette ON recette.id_r = score.recetteid_r WHERE recette.id_r = $id_r");
        return $list_score;
    }

    /** get categ by recette
     * @param $id_r
     * @return \Connector\PDOStatement
     */
    public function get_categ($id_r)
    {
        $list_categ = Connector::prepare("SELECT * FROM recette JOIN category_recette ON category_recette.recetteid_r = recette.id_r JOIN category ON category_recette.categoryid_c = category.id_c WHERE recette.id_r = $id_r");
        return $list_categ;
    }

    /** get categ by recette
     * @param $id_r
     * @return \Connector\PDOStatement
     */
    public function get_categ_edit($id_r)
    {
        $list_categ = Connector::prepare("SELECT * FROM recette JOIN category_recette ON category_recette.recetteid_r = recette.id_r JOIN category ON category_recette.categoryid_c = category.id_c WHERE recette.id_r = $id_r");
        return $list_categ;
    }

    /** get categ by name
     * @param $name
     * @return \Connector\PDOStatement
     */
    public function get_categ_by_name($name)
    {
        $categ = Connector::prepare("SELECT category.id_c FROM category WHERE category.name_c = ?", array($name));
        return $categ;
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
        $my_categ = explode(",",$categ);
        for ($i = 0; $i < count($my_categ); $i++)
        {
            if ($this->check_if_categ_exist($my_categ[$i]) == 1)
            {
                try {
                    $last_r = $this->get_last_id_rec()->fetch();
                    $c = $this->get_categ_by_name($my_categ[$i])->fetchAll();
                    for ($j = 0; $j < count($c); $j++)
                    {
                        $result = $this->insert_lien_c_r($c[$j]['id_c'],$last_r['id_r']);
                    }
                } catch (PDOException $e) {
                    return ($e->getMessage());
                }
            }
            else{
                try {
                    $insert = Connector::prepare("INSERT INTO category(name_c) VALUES(?)", array($my_categ[$i]));
                    $last_r = $this->get_last_id_rec()->fetch();
                    $last_c = $this->get_last_id_categ()->fetch();
                    $result = $this->insert_lien_c_r($last_c['id_c'],$last_r['id_r']);
                } catch (PDOException $e) {
                    return ($e->getMessage());
                }
            }
        }
        return $result;
    }

    /** get last recette
     * @return \Connector\PDOStatement
     */
    public function get_last_id_rec()
    {
        $last_id = Connector::prepare("SELECT recette.id_r, recette.title FROM recette ORDER BY recette.id_r DESC LIMIT 1");
        return $last_id;
    }

    /** get last categ
     * @return \Connector\PDOStatement
     */
    public function get_last_id_categ()
    {
        $last_id_r = Connector::prepare("SELECT category.id_c FROM category ORDER BY category.id_c DESC LIMIT 1");
        return $last_id_r;
    }

    /** query to add new ingre
     * @param $ingre
     * @param $qte
     * @return string
     */
    private function add_new_ingre_query($ingre, $qte, $idr)
    {
        try {
            $res = Connector::prepare("INSERT INTO ingredient(name_in,qt,recetteid_r) VALUES(?,?,?)", array($ingre, $qte,$idr));
            return $res;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** add new ingre
     * @param $ingre
     * @param $qte
     * @return \Connector\PDOStatement|string
     */
    public function add_ingre($ingre, $qte, $idr)
    {
        try {
            for ($i = 0; $i < count($ingre); $i++)
            {
                $query = $this->add_new_ingre_query($ingre[$i],$qte[$i],$idr);
            }
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** query to add new step
     * @param $step
     * @return string
     */
    private function add_new_step_query($step,$idr)
    {
        try {
            $res = Connector::prepare("INSERT INTO step(name_step,recetteid_r) VALUES(?,?)", array($step,$idr));
            return $res;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** add new step
     * @param $step
     * @return \Connector\PDOStatement|string
     */
    public function add_step($step,$idr)
    {
        try {
            for ($i = 0; $i < count($step); $i++)
            {
                $query = $this->add_new_step_query($step[$i],$idr);
            }
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** delete recette
     * @param $idr
     * @return \Connector\PDOStatement|string
     */
    public function del_recipient($idr)
    {
        try {
            $query = Connector::prepare("DELETE FROM recette WHERE recette.id_r = ?", array($idr));
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** update title
     * @param $idr
     * @param $value
     * @return \Connector\PDOStatement|string
     */
    public function update_title($idr, $value)
    {
        try {
            $query = Connector::prepare("UPDATE recette SET title = ? WHERE recette.id_r = ?", array($value, $idr));
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** update type dish
     * @param $idr
     * @param $value
     * @return \Connector\PDOStatement|string
     */
    public function update_type_dish($idr, $value)
    {
        try {
            $query = Connector::prepare("UPDATE recette SET type_dish = ? WHERE recette.id_r = ?", array($value, $idr));
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** update diff
     * @param $idr
     * @param $value
     * @return \Connector\PDOStatement|string
     */
    public function update_diff($idr, $value)
    {
        try {
            $query = Connector::prepare("UPDATE recette SET difficulty = ? WHERE recette.id_r = ?", array($value, $idr));
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** update cost
     * @param $idr
     * @param $value
     * @return \Connector\PDOStatement|string
     */
    public function update_cost($idr, $value)
    {
        try {
            $query = Connector::prepare("UPDATE recette SET cost = ? WHERE recette.id_r = ?", array($value, $idr));
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** update tmp prep
     * @param $idr
     * @param $value
     * @return \Connector\PDOStatement|string
     */
    public function update_tmp_prep($idr, $value)
    {
        try {
            $query = Connector::prepare("UPDATE recette SET time_prep = ? WHERE recette.id_r = ?", array($value, $idr));
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** update tmp cook
     * @param $idr
     * @param $value
     * @return \Connector\PDOStatement|string
     */
    public function update_tmp_cook($idr, $value)
    {
        try {
            $query = Connector::prepare("UPDATE recette SET cook_time = ? WHERE recette.id_r = ?", array($value, $idr));
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** update nb port
     * @param $idr
     * @param $value
     * @return \Connector\PDOStatement|string
     */
    public function update_nb_port($idr, $value)
    {
        try {
            $query = Connector::prepare("UPDATE recette SET nb_port = ? WHERE recette.id_r = ?", array($value, $idr));
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** update drink
     * @param $idr
     * @param $value
     * @return \Connector\PDOStatement|string
     */
    public function update_drink($idr, $value)
    {
        try {
            $query = Connector::prepare("UPDATE recette SET drink = ? WHERE recette.id_r = ?", array($value, $idr));
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** update note (remarque)
     * @param $idr
     * @param $value
     * @return \Connector\PDOStatement|string
     */
    public function update_note($idr, $value)
    {
        try {
            $query = Connector::prepare("UPDATE recette SET note = ? WHERE recette.id_r = ?", array($value, $idr));
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** update vegetarian
     * @param $idr
     * @param $value
     * @return \Connector\PDOStatement|string
     */
    public function update_vege($idr, $value)
    {
        try {
            $query = Connector::prepare("UPDATE recette SET vegetarian = ? WHERE recette.id_r = ?", array($value, $idr));
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** delete ingre
     * @param $idr
     * @param $id_in
     * @return \Connector\PDOStatement|string
     */
    public function del_ingre($idr, $id_in)
    {
        try {
            $query = Connector::prepare("DELETE FROM ingredient WHERE ingredient.id_in = ? AND ingredient.recetteid_r = ?", array($id_in, $idr));
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** delete step
     * @param $idr
     * @param $id_in
     * @return \Connector\PDOStatement|string
     */
    public function del_step($idr, $id_in)
    {
        try {
            $query = Connector::prepare("DELETE FROM step WHERE step.id_step = ? AND step.recetteid_r = ?", array($id_in, $idr));
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** delete categ
     * @param $idr
     * @param $id_c
     * @return \Connector\PDOStatement|string
     */
    public function del_categ_from_rec($idr, $id_c)
    {
        try {
            $query = Connector::prepare("DELETE FROM category_recette WHERE category_recette.categoryid_c = ? AND category_recette.recetteid_r = ?", array($id_c, $idr));
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** delete img
     * @param $idr
     * @param $id_img
     * @return \Connector\PDOStatement|string
     */
    public function del_img_from_rec_sql($idr, $id_img)
    {
        try {
            $query = Connector::prepare("DELETE FROM img WHERE img.id_img = ? AND img.recetteid_r = ?", array($id_img, $idr));
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
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

    /** new score
     * @param $idr
     * @param $score
     * @return \Connector\PDOStatement|string
     */
    public function newScore($idr, $score)
    {
        try {
            $query = Connector::prepare("INSERT INTO score(value, recetteid_r) VALUES (?,?)", array($score, $idr));
            return $query;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    public function search($searchText, $dish, $ingre, $categ, $cost, $diff)
    {
        $query = "SELECT * FROM ((recette JOIN category_recette ON category_recette.recetteid_r = recette.id_r JOIN category ON category_recette.categoryid_c = category.id_c)
                    JOIN img ON recette.id_r = img.recetteid_r) LEFT JOIN ingredient ON recette.id_r = ingredient.recetteid_r WHERE";
        if($searchText != "" || $dish != "" || $ingre != "" || $categ != "" || $cost != "" || $diff != "") {
            if($searchText != "") {
                $query = $query." recette.title LIKE %$searchText% AND ";
            }
            if($dish != "") {
                $query = $query." recette.type_dish = $dish AND ";
            }
            if($ingre != "") {
                $query = $query." ingredient.name_in = $ingre AND ";
            }
            if($categ != "") {
                $query = $query." category.name_c = $categ AND ";
            }
            if($cost != "") {
                $query = $query." recette.cost = $cost AND ";
            }
            if($diff != "") {
                $query = $query." recette.difficulty = $diff AND ";
            }
            try {
                $result = Connector::prepare($query);
                return $result;
            } catch (PDOException $e) {
                return ($e->getMessage());
            }
        }
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

    public function check_id_exist($login)
    {
        $result = Connector::prepare("select * from user where login = ?", array($login));
        return $result;
    }

    /** inscription of a new user
     * @param $id
     * @param $passwd
     * @param $last_name
     * @param $firs_name
     * @param $adress
     * @param $cp
     * @param $ville
     * @param $birthday
     * @return \Connector\PDOStatement|string
     */
    public function inscription_user($id, $passwd, $last_name, $firs_name, $adress, $cp, $ville, $birthday)
    {
        try {
            $res = Connector::prepare("INSERT INTO user(login,mdp,last_name,first_name,adress,cp,ville,birthday) VALUES(?,?,?,?,?,?,?,?)", array($id, $passwd, $last_name, $firs_name, $adress, $cp, $ville, $birthday));
            return $res;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** import img
     * @param $url
     * @param $id
     * @return \Connector\PDOStatement
     */
    public function import_img($url, $id)
    {
        $result = Connector::prepare("insert into img VALUES ('', ?, ?) ", array($url, $id));
        return $result;
    }
}