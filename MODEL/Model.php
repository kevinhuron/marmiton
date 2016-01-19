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

    public function get_all_recette_title($recette_title)
    {
        $list_title_recette = Connector::prepare("SELECT * FROM recette INNER JOIN img ON recette.id_r = img.recetteid_r WHERE recette.title LIKE '%$recette_title%' GROUP BY recette.id_r");
        return $list_title_recette;
    }

    public function get_content_recette($id_r)
    {
        $list_title_recette = Connector::prepare("SELECT * FROM recette JOIN category_recette ON category_recette.recetteid_r = recette.id_r JOIN category ON category_recette.categoryid_c = category.id_c LEFT JOIN img ON img.recetteid_r = recette.id_r LEFT JOIN ingredient ON ingredient.recetteid_r = recette.id_r LEFT JOIN step ON step.recetteid_r = recette.id_r LEFT JOIN score ON score.recetteid_r = recette.id_r WHERE recette.id_r = $id_r GROUP BY recette.id_r");
        return $list_title_recette;
    }
}