<?php


/**
 * Created by PhpStorm.
 * User: DanyRafina
 * Date: 18/12/2015
 * Time: 09:50
 */

class Autoloader {

    static public function register($class)
    {
        /// Je donne le chemin du projet
        $path = realpath('.');
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {

            /// Recursive iterator iterator avec la classe RecursiveDirecotory Iterator va me donner chaque fichier dans les sous dossiers
            if (strpos($filename, '.php') !== false && !strpos($filename, 'PRIVATE') && !strpos($filename, 'ENGINE_YAML')) {
                /// La je regarde  si le fichier c'est un php et que ça ne fait pas parti du dossier private car ya Twig et que ma class recu en parametre est contenu dans le dossier
                require_once $filename;
                //return ;
            }
        }
    }
}

/// spl_autoload_register va en faite me retourer la classe appellé c-a-d si j'ai besoin d'une classe dans un fichier , alors spl_autoloader est appellé et va appelé
// Autoloader::register pour charger ma classe
spl_autoload_register('\Autoloader::register');