<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class arenda_metro_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'arenda_metro/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}                 
}