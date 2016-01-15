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
    public function get_all_recette()
    {

    }









    /** Get all database
     * @return array
     */
    public function get_all_db()
    {
        $list_db = Connector::prepare("SELECT create_time as crea, sum(data_length + index_length) / 1024 as memory, COUNT(*) as nb, table_schema as db FROM information_schema.tables GROUP BY table_schema;");
        return $list_db;
    }

    /** Get all database by name
     * @return array
     */
    public function get_all_db_name()
    {
        $list_db = Connector::prepare("SELECT SCHEMA_NAME AS `Database` FROM INFORMATION_SCHEMA.SCHEMATA");
        return $list_db;
    }

    /** get all tables of a DB
     * @param $dbname
     * @return PDOStatement
     */
    public function get_tables($dbname)
    {
        $list_tables = Connector::prepare("SELECT table_name, data_length, table_rows FROM information_schema.tables WHERE table_schema= ? ", array($dbname));
        return $list_tables;
    }

    /** get struct of a tables
     * @param $dbname
     * @param $t_name
     * @return PDOStatement
     */
    public function get_tables_struct($dbname, $t_name)
    {
        $tables_struct = Connector::prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema= ? AND TABLE_NAME = ?", array($dbname, $t_name));
        return $tables_struct;
    }

    /** request add new db
     * @param $newDBname
     * @return PDOStatement|string
     */
    public function add_new_db($newDBname)
    {
        $controller = new Controller();
        try {
            $sql = "CREATE DATABASE $newDBname;";
            $result = Connector::prepare($sql, NULL);
            $controller->write_log_sql($sql);
            return $result;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
        unset($controller);
    }

    /** add a new field
     * @param $db
     * @param $table
     * @param $field_info
     * @return PDOStatement|string
     */
    public function add_field($db, $table, $field_info)
    {
        $str = "ALTER TABLE $db.$table ADD ";
        $str = $str.$field_info[0]." ".$field_info[1];
        $str = ($field_info[2] != "NV")? $str."(".$field_info[2].")" : $str."";
        $str = ($field_info[4] == "no")? $str." NOT NULL" : $str." NULL";
        if ($field_info[3] == "NULL")
            $str = $str." DEFAULT NULL";
        else if ($field_info[3] == "CURRENT_TIMESTAMP")
            $str = $str." DEFAULT CURRENT_TIMESTAMP";
        else if ($field_info[3] == "Aucune")
            $str = $str."";
        else
            $str." DEFAULT '".$field_info[3]."'";
        $str = ($field_info[6] == "no")? $str."" : $str." auto_increment";
        if ($field_info[5] == "PRIMARY")
            $str = $str." PRIMARY KEY";
        else
            ($field_info[5] == "UNIQUE")? $str." UNIQUE" : $str."";
        try {
            $result = Connector::prepare($str, NULL);
            return $result;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** Migrate all database tables
     * @param $dbname New database name
     * @param $db_drop Database to migrate
     * @return PDOStatement|string
     */
    public function migrate_db($dbname, $db_drop)
    {
        try {
            $get_tables = $this->get_tables($db_drop);
            while ($line = $get_tables->fetch())
                $result = Connector::prepare("RENAME TABLE $db_drop.".$line['table_name'] ." TO $dbname.".$line['table_name'], NULL);
            return $result;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** request delete DB
     * @param $db
     * @return PDOStatement|string
     */
    public function drop_db($db)
    {
        $controller = new Controller();
        try {
            $sql = "DROP DATABASE`$db`;";
            $result = Connector::prepare($sql, NULL);
            $controller->write_log_sql($sql);
            return $result;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
        unset($controller);
    }

    /** delete a data
     * @param $db
     * @param $table
     * @param $id_col_name
     * @param $id_field
     * @return PDOStatement|string
     */
    public function delete_data($db, $table, $id_col_name, $id_field)
    {
        $controller = new Controller();
        try {
            $sql = "DELETE FROM $db.$table WHERE $table.$id_col_name = $id_field;";
            $result = Connector::prepare("DELETE FROM $db.$table WHERE $table.$id_col_name = ?", array($id_field));
            $controller->write_log_sql($sql);
            return $result;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
        unset($controller);
    }

    /** edit a data
     * @param $db
     * @param $table
     * @param $id_col_name
     * @param $col_name_edit
     * @param $id_value
     * @param $value
     * @return PDOStatement|string
     */
    public function edit_data($db, $table, $id_col_name, $col_name_edit, $id_value, $value)
    {
        $controller = new Controller();
        try {
            $sql = "UPDATE $db.$table SET $col_name_edit = $value WHERE $table.$id_col_name = $id_value;";
            $result = Connector::prepare("UPDATE $db.$table SET $col_name_edit = ? WHERE $table.$id_col_name = ?;", array($value, $id_value));
            $controller->write_log_sql($sql);
            return $result;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
        unset($controller);
    }

    /** request delete table
     * @param $dbname
     * @param $table
     * @return PDOStatement|string
     */
    public function drop_table($dbname, $table)
    {
        $controller = new Controller();
        try {
            $sql = "drop table $dbname.$table";
            $result = Connector::prepare("drop table $dbname.$table", NULL);
            $controller->write_log_sql($sql);
            return $result;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
        unset($controller);
    }

    /** function to add new data
     * @param $dbname
     * @param $table
     * @param $field_name
     * @param $new_data
     * @return PDOStatement|string
     */
    public function add_new_data($dbname, $table, $field_name, $new_data)
    {
        //$controller = new Controller();
        try {
            $query = $this->add_new_data_query($dbname, $table, $field_name, $new_data);
            return Connector::prepare($query, $new_data);
            //$controller->write_log_sql($query);
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
        //unset($controller);
    }

    /** query for add new data
     * @param $dbname
     * @param $table
     * @param $field_name
     * @param $new_data
     * @return string
     */
    private function add_new_data_query($dbname, $table, $field_name, $new_data)
    {
        $cField = count($field_name);
        $cData = count($new_data);
        $str = "INSERT INTO $dbname.$table (";
        for ($i = 0; $i < count($field_name); $i++)
        {
            $str = $str.$field_name[$i];
            $str = ($cField != ($i + 1))?  $str." , " : $str."";

        }
        $str = $str." ) VALUES ( ";
        for ($i = 0; $i < count($new_data); $i++)
        {
            $str = $str.'?';
            $str = ($cData != ($i + 1))?  $str." , " : $str."";
        }
        $str = $str.");";
        return $str;
    }

    /** request rename table
     * @param $dbname
     * @param $table
     * @param $new_name_table
     * @return PDOStatement|string
     */
    public function rename_the_table($dbname, $table, $new_name_table)
    {
        $controller = new Controller();
        try {
            $sql = "RENAME TABLE $dbname.$table TO $dbname.$new_name_table;";
            $result = Connector::prepare("RENAME TABLE $dbname.$table TO $dbname.$new_name_table", NULL);
            $controller->write_log_sql($sql);
            return $result;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
        unset($controller);
    }


    /** Create table and rows
     * @param $dbname
     * @param $table
     * @param $rows
     * @return PDOStatement|string
     */
    public function create_table($dbname, $table, $rows)
    {
        $controller = new Controller();
        try {
            $query = $this->create_query_table($rows, $dbname, $table);
            $controller->write_log_sql($query);
            return Connector::prepare($query, NULL);
        } catch (PDOException $e) {
            return ($e->getMessage());
            unset($controller);
        }
    }

    /** the query of the textarea
     * @param null $rows
     * @param null $dbname
     * @param null $table
     * @return string
     */
    private function create_query_table($rows = null, $dbname = null, $table = null)
    {
        $c = count($rows);
        $str = "CREATE TABLE $dbname.$table ( ";
        for ($i = 0; $i < count($rows); $i++)
        {
            $str = $str.$rows[$i][0]." ".$rows[$i][1];
            $str = ($rows[$i][2] != "NV")? $str."(".$rows[$i][2].")" : $str."";
            $str = ($rows[$i][4] == "no")? $str." NOT NULL" : $str." NULL";
            if ($rows[$i][3] == "NULL")
                $str = $str." DEFAULT NULL";
            else if ($rows[$i][3] == "CURRENT_TIMESTAMP")
                $str = $str." DEFAULT CURRENT_TIMESTAMP";
            else if ($rows[$i][3] == "Aucune")
                $str = $str."";
            else
                $str." DEFAULT '".$rows[$i][3]."'";
            $str = ($rows[$i][6] == "no")? $str."" : $str." auto_increment";
            if ($rows[$i][5] == "PRIMARY")
                $str = $str." PRIMARY KEY";
            else
                ($rows[$i][5] == "UNIQUE")? $str." UNIQUE" : $str."";
            $str = ($c != ($i + 1))?  $str." , " : $str."";
        }
        $str = $str." );";
        return $str;
    }

    /** the textarea for custom query
     * @param $query
     * @return PDOStatement|string
     */
    public function custom_query($query)
    {
        $controller = new Controller();
        try {
            $result = Connector::prepare($query, NULL);
            $controller->write_log_sql($query);
            return $result;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
        unset($controller);
    }

    /** get data of a table
     * @param $dbname
     * @param $table
     * @return PDOStatement|string
     */
    public function get_content_table($dbname, $table)
    {
        $controller = new Controller();
        try {
            $sql = "SELECT * FROM $dbname.$table;";
            $result = Connector::prepare("SELECT * FROM $dbname.$table", NULL);
            $controller->write_log_sql($sql);
            return $result;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
        unset($controller);
    }

    /** drop a field
     * @param $db
     * @param $table
     * @param $field_name
     * @return PDOStatement|string
     */
    public function drop_field($db, $table, $field_name)
    {
        $controller = new Controller();
        try {
            $sql = "ALTER TABLE $db.$table DROP COLUMN $field_name;";
            $result = Connector::prepare("ALTER TABLE $db.$table DROP COLUMN $field_name;", NULL);
            $controller->write_log_sql($sql);
            return $result;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
        unset($controller);
    }

    /** edit a field
     * @param $request
     * @return PDOStatement|string
     */
    public function edit_field($request)
    {
        $controller = new Controller();
        try {
            $str = "ALTER TABLE ".$request['name_db'].".".$request['table_name']." CHANGE ";
            $str = $str.$request['odl_field_name']." ".$request['new_field_name']." ".$request['new_type_field']."(".$request['new_size_field'].")";
            $str = ($request['new_isNull_field'] == "NO")? $str." NOT NULL" : $str." NULL";
            if ($request['new_default_field'] == "NULL")
                $str = $str." DEFAULT NULL";
            else if ($request['new_default_field'] == "CURRENT_TIMESTAMP")
                $str = $str." DEFAULT CURRENT_TIMESTAMP";
            else if ($request['new_default_field'] == "Aucune")
                $str = $str."";
            else
                $str." DEFAULT '".$request['new_default_field']."'";
            $controller->write_log_sql($str);
            $result = Connector::prepare($str, NULL);
            return $result;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
        unset($controller);
    }

    /** check if table exist
     * @param $dbname
     * @param $table
     * @return PDOStatement|string
     *
     */
    public function check_table_exist($dbname, $table)
    {
        try {
            $result = Connector::prepare("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = ? AND TABLE_SCHEMA= ?", array($table, $dbname));
            return $result;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** get the server
     * @return PDOStatement
     */
    public function get_server_version()
    {
        $version = Connector::prepare("select version()");
        return $version;
    }

    /** get the user connected
     * @return PDOStatement
     */
    public function get_user_co()
    {
        $version = Connector::prepare("select user()");
        return $version;
    }

    /** check if db exist
     * @param $dbname
     * @return PDOStatement|string
     */
    public function check_database_exist($dbname)
    {
        try {
            $result = Connector::prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'", NULL);
            return $result;
        } catch (PDOException $e) {
            return ($e->getMessage());
        }
    }

    /** get the charset
     * @return PDOStatement
     */
    public function get_charset()
    {
        $version = Connector::prepare("select charset(user())");
        return $version;
    }

    /** make the connection at login
     * @return PDOStatement
     */
    public function make_login_connector()
    {
        $result = Connector::prepare("show databases");
        return $result;
    }
}