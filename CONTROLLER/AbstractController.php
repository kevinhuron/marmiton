<?php

/**
 * Created by PhpStorm.
 * User: rafina
 * Date: 09/01/16
 * Time: 21:35
 */

namespace AbsSupervisor;
use DataLayer\Model;

abstract class AbstractController
{
    protected $model_instance = NULL;


    /** This is a Singleton
     * Get an instance of model
     * @return Model Instance of Model Class
     */
    protected function getModel()
    {
        return ($this->model_instance == NULL) ? $this->model_instance = new Model() : $this->model_instance;
    }
}