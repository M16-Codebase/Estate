<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class land_sdelkaland_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'land_sdelkaland/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}                 
}