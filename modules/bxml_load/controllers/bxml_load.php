<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//ini_set('display_errors', 1);

/**
 * Класс осущетсвляет загрузку данных из xml файла
 * и выгрузку их в БД
 * Вся логика будет прямо в конструкторе
 * Мудрить какие-то хитросплетения смысла не вижу
 * 
 * */

class bxml_load extends MY_Controller {
    
    const MOD_NAME = 'bxml_load';
    
    protected $mod;
    private $loader;
    
    /**
     * 
     * 
     * 
     */
    function __construct() {
        parent::__construct();
        include(MDPATH . self::MOD_NAME . '/moduleinfo.php');
        $this->mod = $mInfo['name'];
        $this->config = $this->load->config(self::MOD_NAME . '/' . self::MOD_NAME, true);
        $this->load->model($this->mod . '/xml_load_model', 'factory');
        $this->loader = $this->factory->getLoader($this->config['xml_load_method']);
    }
    
    
    
    public function index() {
        
		$this->loader->loadData();
	}
    
    public function sync() {
		$this->loader->syncData();
	}
    
    
    
}


/*
public function construct() {
        parent::__construct();
        
        //include(MDPATH . 'xml_load/moduleinfo.php');
        
        //$this->module = $moduleinfo['name'];
        //$this->config = $this->load->config($this->module . '/' . $this->module, true);
        //var_dump($this->module);
        
        
        
        //$this->load->model($this->module . '/xml_load_model', 'model');
        //$controller->load->model($moduleinfo['name'] . '/xml_load' . $this->config->xml_load_method . '_model', 'model');
        //$this->load->model('buildings/buildings_model','buildings_model');
        
        
    }

*/

