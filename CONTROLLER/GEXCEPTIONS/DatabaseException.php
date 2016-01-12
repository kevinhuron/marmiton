<?php

/**
 * Created by PhpStorm.
 * User: rafina
 * Date: 29/12/15
 * Time: 00:57
 * Class DatabaseException
 * This class generate a exception about Database
 */
class DatabaseException extends Exception
{
    /**
    * DatabaseException constructor.
    * Create an instance of DatabaseException
    * @param string $message The exception message
    * @param int $databases List of database
    */
    public function __construct($message, $databases)
    {
        echo $_SESSION['twig']->render("error.html.twig", array("error" => $message, "alldbname" => $databases));
    }
}