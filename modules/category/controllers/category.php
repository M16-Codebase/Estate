<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модуль: Категории новостей
**/

class category extends MY_Controller {     
    
	private $conf; // конфиг файл
    private $lang; // языковый файл    
    
    function __construct()
	{
		// конструктор
		parent::__construct();
        
        include(MDPATH.'category/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];
        
        // определяем, нужно ли использовать роутер
        if(!empty($moduleinfo['router']))
            { $this->link = $moduleinfo['router']; } 
        else 
            { $this->link = $this->module; }
	}
    
    /**
     * Вытаскиваем определенный массив значений с возможностью задать критерий выборки
    **/
    function infoPage($where = array(), $mas)
    {
        $this->load->model($this->table.'/'.$this->table.'_model','razdel_siteModel'); // модели     
        $bd = $this->razdel_siteModel->all_data_where($this->table,$where,'sort','asc');

        $result = '';
        $i=0;

        if(is_array($mas))
        {
            $co = count($mas);

            if(count($bd) > 0 and $co > 0)
            {
                foreach($bd as $r)
                {
                    foreach($mas as $m)
                    {
                        $result[$r['id']][$m] = $r[$m];
                    }
                }
            }
        }
        else
        {
            if(count($bd) > 0)
            {
                foreach($bd as $r)
                {
                    $result[$r['id']] = $r[$mas];
                }
            }
        }

        return $result;
    }
}
/* End of file */