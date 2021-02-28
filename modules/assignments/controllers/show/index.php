<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * Модуль pages | Админ
 * Страницы
 *
 **/

class Index extends MY_Assignments
{

    function __construct()
    {
        // конструктор
        parent::__construct();

    }

    function index() {
        if ($this->dx_auth->is_assignment()){
            echo 666;
        }
        $this->load->view('./assignments/index', array('s'=>1));
    }

}