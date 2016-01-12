<?php
/**
 * Created by PhpStorm.
 * User: DanyRafina
 * Date: 18/12/2015
 * Time: 09:50
 */

class Starter {

    public function __construct() {
        $this->__autoloader();
        $this->initTwig();
    }

    /** autoloader
     */
    public function __autoloader() {
        include_once 'Autoloader.php';
    }

    /** init TWIG
     *
     */
    public function initTwig()
    {
        try {
            include_once 'PRIVATE/Twig/vendor/autoload.php';
            $_SESSION['twig'] = new Twig_Environment(new Twig_Loader_Filesystem('VIEWS'), array('cache' => false, 'debug' => true));
            $_SESSION['twig']->addExtension(new Twig_Extension_Debug());
        } catch (Exception $e) {
            $error = "Twig n'est pas disponible";
            include 'VIEWS/error.html.twig';
        }
    }
}
