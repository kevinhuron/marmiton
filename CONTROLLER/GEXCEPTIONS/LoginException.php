<?php

/**
 * Created by PhpStorm.
 * User: rafina
 * Date: 29/12/15
 * Time: 00:57
 * Class DatabaseException
 * This class generate a exception about Database
 */
class LoginException extends Exception
{
    /**
    * DatabaseException constructor.
    * Create an instance of DatabaseException
    * @param string $message The exception message
    * @param int $databases List of database
    */
    public function __construct()
    {
        unset($_SESSION['login']);
        unset($_SESSION['passwd']);
        echo $_SESSION['twig']->render("login.html.twig", array("error" => "Identifiant ou mot de passe incorrect"));
    }
}