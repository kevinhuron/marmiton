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
        $model = $this->getModel();
        echo $_SESSION['twig']->render("index.html.twig");
        unset($model);
    }


    /** get list of tables
     * @return array
     */
    private function get_list_tab()
    {
        $listOfTable = array();
        $model = $this->getModel();
        $databases = $model->get_all_db()->fetchAll();
        $alldbname = $model->get_all_db_name()->fetchAll();
        $mergeDB = $this->merge_databases($databases,$alldbname);
        for ($i = 0; $i < count($mergeDB); $i++)
            array_push($listOfTable, array($mergeDB[$i]['db'], $this->get_table_name($mergeDB[$i]['db'])));
        return $listOfTable;
        unset($model);
    }

    /** get name of list of tables
     * @param $dbname
     * @return array
     */
    private function get_table_name($dbname)
    {
        $model = $this->getModel();
        $tables_names = array();
        $table = $model->get_tables($dbname);
        while ($line = $table->fetch())
            array_push($tables_names, $line['table_name']);
        return $tables_names ;
    }

    /** Merge the two list of databases
     * @param $dbs1
     * @param $dbs2
     * @return mixed
     */
    private function merge_databases($dbs1, $dbs2)
    {
        for ($k = 0; $k < count($dbs2); $k++) {
            if ($this->list_db($dbs1, $dbs2[$k]['Database']) == 0)
                array_push($dbs1, array("db" => $dbs2[$k]['Database'], "nb" => 0, "crea" => NULL, "memory" => 0));
        }
        return ($dbs1);
    }

    /** list db
     * @param $list
     * @param $arg
     * @return int
     */
    private function list_db($list, $arg)
    {
        for ($i = 0; $i < count($list); $i++) {
            if ($arg == $list[$i]["db"])
                return (1);
        }
        return (0);
    }

    /** return les logs
     * @return array
     */
    public function getLogs()
    {
        $file = fopen("CONTROLLER/LOG/log.txt", "r");
        $log = array();
        while ($read = fgets($file, 8192))
            array_push($log, $read);
        fclose($file);
        return array_reverse($log);
    }

    /** return les logs
     * @return array
     */
    public function getLogs_sql()
    {
        $file = fopen("CONTROLLER/LOG/log_sql.txt", "r");
        $log = array();
        while ($read = fgets($file, 8192))
            array_push($log, $read);
        fclose($file);
        return array_reverse($log);
    }


    /** show tables of a DB
     * @param $dbname
     * @throws DatabaseException
     */
    public function showDB($dbname)
    {
        $model = $this->getModel();
        $list_table = $this->get_list_tab();
        $log_sql = $this->getLogs_sql();
        if ($model->check_database_exist($dbname)->fetch() != NULL) {
            $tables = $model->get_tables($dbname)->fetchAll();
            echo $_SESSION['twig']->render("db_info.html.twig",
                array("alldbname" => $list_table, "tables" => $tables, "dbname" => $dbname,"log_sql" => $log_sql));
        } else
            throw new DatabaseException("La base de données n'existe pas !", $list_table);
        unset($model);
    }

    /** show struct of a table
     * @param $dbname
     * @param $t_name
     * @throws TableException
     */
    public function showTableStruct($dbname, $t_name)
    {
        $model = $this->getModel();
        $list_table = $this->get_list_tab();
        $log_sql = $this->getLogs_sql();
        if ($model->check_table_exist($dbname, $t_name)->fetch() != NULL) {
            $tables_struct = $model->get_tables_struct($dbname, $t_name)->fetchAll();
            echo $_SESSION['twig']->render("table_struct.html.twig",
                array("alldbname" => $list_table, "tables_struct" => $tables_struct, "t_name" => $t_name,"dbname" => $dbname,"log_sql" => $log_sql));
        } else
            throw new TableException("La table n'existe pas dans cette base!", $t_name, $list_table);
        unset($model);
    }

    /** get gv
     * @param $type
     * @param $size
     * @return int
     */
    private function get_gv($type, $size)
    {
        if ($type == "VARCHAR" && $size == "NV")
            return (1);
        else if (($type == "DATE" && $size != "NV") ||
            ($type == "TIMESTAMP" && $size != "NV") ||
            ($type == "DATETIME" && $size != "NV") ||
            ($type == "BOOLEAN" && $size != "NV") ||
            ($type == "TIME" && $size != "NV"))
            return (-1);
        else
            return (0);
    }

    /** make a echo
     * @param $val
     */
    private function make_echo($val)
    {
        echo $val;
        return ;
    }

    /** add new field
     * @param $request
     */
    public function add_field($request)
    {
        $table = $request["name_table"];
        $db = $request["namedb"];
        $test = $this->get_gv($request['type'], $request['size']);
        if ($test == 1)
            return $this->make_echo("[ERROR ON MPMA] VARCHAR must be have a size");
        else if ($test == -1)
            return $this->make_echo("[ERROR ON MPMA] ".$request['type']." does not have size.");
           $field_info =  array($request["name"], $request['type'], $request['size'],
                ($request['default'] != "def") ? $request['default'] : $request['def_i'],
                $request['is_n'], $request['index'], $request['ai']);
        $model = $this->getModel();
        $result = $model->add_field($db, $table, $field_info);
        if ($result->errorInfo()[1] == NULL)
        {
            $this->writeFile("La colonne ".$request["name"]." a été créé dans la table $table de la base $db");
            echo 1;
        }
        else
            $this->return_error($result);
        unset($model);
    }

    /** add new table
     * @param $request
     */
    public function add_table($request)
    {
        $add_t = array();
        $table = $request["name_table"];
        $db = $request["namedb"];
        for ($i = 0; $i < count($request['name']); $i++) {
            $test = $this->get_gv($request['type'][$i], $request['size'][$i]);
            if ($test == 1)
                return $this->make_echo("[ERROR ON MPMA] VARCHAR must be have a size");
            else if ($test == -1)
                return $this->make_echo("[ERROR ON MPMA] ".$request['type'][$i]." does not have size.");
                    array_push($add_t, array($request["name"][$i], $request['type'][$i], $request['size'][$i],
                        ($request['default'][$i] != "def") ? $request['default'][$i] : $request['def_i'][$i],
                        $request['is_n'][$i], $request['index'][$i], $request['ai'][$i]));
        }
        $model = $this->getModel();
        $result = $model->create_table($db, $table, $add_t);
        if ($result->errorInfo()[1] == NULL)
        {
            $this->writeFile("La table $table a été créé dans la base $db");
            echo 1;
        }
        else
            $this->return_error($result);
        unset($model);
    }

    /** make a query
     * @param $query
     */
    public function make_query($query)
    {
        $model = $this->getModel();
        $result = $model->custom_query($query);
        if ($result->errorInfo()[1] == NULL)
            echo json_encode($result->fetchAll(PDO::FETCH_NAMED));
        else
        {
            $error = $result->errorInfo();
            $error = $error[0] . " " . $error[1] . "  " . $error[2];
            echo json_encode(array("error"=>$error ,"code" => -1));
        }
        unset($model);
    }

    /** delete field
     * @param $dbname
     * @param $table
     * @param $field_name
     */
    public function delete_field($dbname, $table, $field_name)
    {
        $model = $this->getModel();
        $result = $model->drop_field($dbname, $table, $field_name);
        if ($result->errorInfo()[1] == NULL) {
            $this->writeFile("Le champs $field_name de la table $table de la base de données $dbname a été supprimée");
            echo 1;
        } else
            $this->return_error($result);
        unset($model);
    }

    /** add new data
     * @param $dbname
     * @param $table
     * @param $field_name
     * @param $new_data
     */
    public function add_data($dbname, $table, $field_name, $new_data)
    {
        $model = $this->getModel();
        $result = $model->add_new_data($dbname, $table, $field_name, $new_data);
        if ($result->errorInfo()[1] == NULL) {
            $this->writeFile("Une donnée à été ajoutée dans la table $table de la base de données $dbname");
            echo 1;
        } else
            $this->return_error($result);
        unset($model);
    }

    /** edit a field
     * @param $request
     */
    public function edit_field($request)
    {
        $model = $this->getModel();
        $result = $model->edit_field($request);
        if ($result->errorInfo()[1] == NULL) {
            $this->writeFile("Le champs $request[2] à été modifié dans la table $request[1] de la base de données $request[0] en $request[3]");
            echo 1;
        } else
            $this->return_error($result);
        unset($model);
    }

    /** show data of a table
     * @param $dbname
     * @param $t_name
     * @throws TableException
     */
    public function showTableData($dbname, $t_name)
    {
        $model = $this->getModel();
        $list_table = $this->get_list_tab();
        $log_sql = $this->getLogs_sql();
        if ($model->check_table_exist($dbname, $t_name)->fetch() != NULL) {
            $tables_data = $model->get_content_table($dbname, $t_name)->fetchAll();
            $tables_struct = $model->get_tables_struct($dbname, $t_name)->fetchAll();
            echo $_SESSION['twig']->render("table_view.html.twig",
                array("alldbname" => $list_table, "tables_data" => $tables_data, "tables_struct" =>$tables_struct, "t_name" => $t_name,"dbname" => $dbname,
                    "log_sql" => $log_sql));
        } else
            throw new TableException("La table n'existe pas dans cette base!", $t_name, $list_table);
        unset($model);
    }

    /** delete a data
     * @param $db
     * @param $table
     * @param $id_col_name
     * @param $id_field
     */
    public function drop_data($db, $table, $id_col_name, $id_field)
    {
        $model = $this->getModel();
        $result = $model->delete_data($db, $table, $id_col_name, $id_field);
        if ($result->errorInfo()[1] == NULL) {
            $this->writeFile("Une donnée ayant l'id N° $id_field, dans la table $table, dans la base $db à été supprimée");
            echo 1;
        } else
            $this->return_error($result);
        unset($model);
    }

    /** edit a data
     * @param $db
     * @param $table
     * @param $id_col_name
     * @param $col_name_edit
     * @param $id_value
     * @param $value
     */
    /// FUNCTION HAVE A LOT OF PARAMETER
    public function edit_data($db, $table, $id_col_name, $col_name_edit, $id_value, $value)
    {
        $model = $this->getModel();
        $result = $model->edit_data($db, $table, $id_col_name, $col_name_edit, $id_value, $value);
        if ($result->errorInfo()[1] == NULL) {
            $this->writeFile("Une donnée à été changé : $value, d'Id : $id_value, dans la table $table, dans la base $db");
            echo 1;
        } else
            $this->return_error($result);
        unset($model);
    }

    /** form for create new DB
     *
     */
    public function formNewDB()
    {
        $model = $this->getModel();
        $list_table = $this->get_list_tab();
        $log_sql = $this->getLogs_sql();
        echo $_SESSION['twig']->render('addDB.html.twig', array("alldbname" => $list_table,"log_sql" => $log_sql));
        unset($model);
    }

    /** add a new DB
     * @param $newDBname
     */
    public function addDB($newDBname)
    {
        $model = $this->getModel();
        $result = $model->add_new_db($newDBname);
        if ($result->errorInfo()[1] == NULL) {
            $this->writeFile("La base de données $newDBname a été créé");
            echo 1;
        } else
            $this->return_error($result);
        unset($model);
    }

    /** delete a db
     * @param $db
     */
    public function drop_db($db)
    {
        $model = $this->getModel();
        $result = $model->drop_db($db);
        if ($result->errorInfo()[1] == NULL) {
            $this->writeFile("La base de données $db a été supprimée");
            echo 1;
        } else
            $this->return_error($result);
        unset($model);
    }

    /** delete a table from a db
     * @param $dbname
     * @param $table
     */
    public function delete_table($dbname, $table)
    {
        $model = $this->getModel();
        $result = $model->drop_table($dbname, $table);
        if ($result->errorInfo()[1] == NULL) {
            $this->writeFile("La table $table de la base de données $dbname a été supprimée");
            echo 1;
        } else
            $this->return_error($result);
        unset($model);
    }

    /** Rename a table
     * @param $dbname
     * @param $table
     * @param $new_name_table
     */
    public function rename_table($dbname, $table, $new_name_table)
    {
        $model = $this->getModel();
        $result = $model->rename_the_table($dbname, $table, $new_name_table);
        if ($result->errorInfo()[1] == NULL) {
            $this->writeFile("La table $table à été renommée en $new_name_table");
            echo 1;
        } else
            $this->return_error($result);
        unset($model);
    }

    /** rename a DB
     * @param $name_db
     * @param $newdbname
     */
    public function renameDB($name_db, $newdbname)
    {
        $model = $this->getModel();
        $addDB = $model->add_new_db($newdbname);
        //$c = $addDB->rowCount();
        if ($addDB->errorInfo()[1] == NULL)
            echo ($res = $this->do_migration($newdbname, $model, $name_db) == 1) ? $res : 1;
        else
            $this->return_error($addDB);
        unset($model);
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

    /** write logs
     * @param $message
     */
    private function writeFile($message)
    {
        $file = fopen("CONTROLLER/LOG/log.txt", "a");
        fwrite($file, $message . "\n");
        fclose($file);
    }

    /** write logs
     * @param $message
     */
    public function write_log_sql($message)
    {
        $file = fopen("CONTROLLER/LOG/log_sql.txt", "a");
        fwrite($file, $message . "\n");
        fclose($file);
    }

    /** delete the backup
     * @param $filepath
     * @return int|string
     */
    private function delete_backup($filepath)
    {
        try {
            unlink($filepath);
            return (1);
        } catch (Exception $e) {
            return ($e->getMessage());
        }
    }

    /**
     * backup the db
     * @deprecated Use restore_database instead
     * @param $db
     * @return null|string
     */
    private function backup_database($db)
    {
        $filepath = "CONTROLLER/BACKUP/backup_$db.sql";
        try {
            $cmd = "mysqldump -h " . Connector::getInfo("host") . " -u " . Connector::getInfo("username") . "  $db --password=" . Connector::getInfo("password") . " > $filepath";
            exec($cmd);
            return ($filepath);
        } catch (ErrorException $e) {
            return (NULL);
        }
    }

    /** rename the bdd
     * @param $newdbname
     * @param $model
     * @param $name_db
     * @return int|string
     */
    private function do_migration($newdbname, $model, $name_db)
    {
        try {
            $model->migrate_db($newdbname, $name_db);
            $model->drop_db($name_db);
            $this->writeFile("La base de données $name_db a eté rennomée en $newdbname.");
            return (1);
        } catch (Exception $e) {
            $model->drop_db($newdbname);
            $this->writeFile("La base de données $newdbname a été supprimée");
            return ("Impossible de peupler la base");
        }
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