<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/**
 * CodeIgniter Selectlink Class
 */
class Selectlink extends MX_Controller {
   
    public $alang;
    private $ci;
    private $otmetka;
    private $arraySelect;
    private $value;
    public $arrayOptgroup;
    
	function __construct($valueLinkId)
	{        
        // конструктор
		parent::__construct(); 
        
        $this->ci =& get_instance();
        
        $this->value = $valueLinkId;
                        
        if(!$this->session->userdata('languages'))
        {
            $this->session->set_userdata('languages', 'russian');
        }
        
        $lng = $this->session->userdata('languages'); 
        
        // Загрузка языка                
        $foreachs = $this->load->language('admin/apex', $lng, true);
        foreach($foreachs as $key=>$l)
        {
            $apexLang = htmlspecialchars_decode($l['name']);
            $this->alang->$key = $apexLang; // создаем переменные            
        }                                                    
	}
    
    
    function addParams($module, $optgroup, $name = '')
    {
        $this->arrayOptgroup[] = array(
                'module' => $module,
                'optgroup' => $optgroup,
                'name' => $name
            );
    }
    
    function initialize()
    {                   
        $this->otmetka = explode(',' , $this->value);
        $array = $this->arrayOptgroup;
        
        foreach($array as $ar)
        {
            $arrayModule[] = $ar['module'];
        }
        $this->arraySelect = $this->optionSelectData($arrayModule);
        
        $optgroup = "
            <optgroup id=\"%s\" label=\"%s\">
                %s                                        
            </optgroup>
        ";
        
        $templateData = '';
        $templateSelected = '';
                
        foreach($array as $ar)
        {
            $templateData .= sprintf($optgroup,
                    $ar['module'],
                    $ar['optgroup'],
                    $this->optionData($ar['module'], $ar['name'])
                );
            
            $templateSelected .= sprintf($optgroup,
                    $ar['module'],
                    $ar['optgroup'],
                    $this->optionSelected($ar['module'], $ar['name'])
                );
        }
        
        echo $this->createComponent($templateData, $templateSelected);
    }  
    
    
    function optionData($module, $moduleName = '')
    {                
        $mdName = "admin_$module";
        $mdModel = "{$module}/admin_{$module}_model";
        $otmetka = $this->otmetka;
        $return = '';
        
        if(empty($moduleName))
        {
            $this->ci->load->model($mdModel, $mdName);                        
            $dataArray = $this->ci->$mdName->parent_id();                                                            
            foreach($dataArray as $key=>$p)
            {
                if(!in_array("$module-".$p['id'], $otmetka))
                {
                    $return .= "<option value='$module-{$p['id']}'>{$p['name']}</option>";
                }
            }
        }
        else
        {
            $this->ci->load->model($mdModel, $mdName);                        
            $dataArray = $this->ci->$mdName->parent_id();                
            if(!in_array("$module-$module", $otmetka))
            {
                $return .= "<option value='$module-$module'>Страница $moduleName</option>";
            }
            foreach($dataArray as $p)
            {
                if(!in_array("$module-".$p['id'], $otmetka))
                {
                    $return .= "<option value='$module-{$p['id']}'>{$p['name']}</option>";
                }
            }
        }
        
        return $return;
    }
    
    function optionSelectData($array)
    {                               
        $otmetka = $this->otmetka;
        
        foreach($otmetka as $p)
        {
            $exp = explode('-', $p);
            foreach($array as $item)
            {            
                if($exp[0] == $item)
                {
                    $return[$item][] = $exp[1];
                }
            }
        }
        
        return $return;                                                                               
    }
    
    function optionSelected($module, $moduleName = '')
    {
        $mdName = "admin_$module";
        $mdModel = "{$module}/admin_{$module}_model";
        $arraySelect = $this->arraySelect;
        $return = '';
        
        $this->ci->load->model($mdModel, $mdName);                        
        $dataArray = $this->ci->$mdName->parent_id();
        
        if(!empty($arraySelect[$module]))
        {
            if(!empty($moduleName))
            {
                if(in_array($module, $arraySelect[$module]))
                {
                    $return .= "<option value='$module-$module'>Страница $moduleName</option>";
                }
            }
            foreach($dataArray as $p)
            {
                if(in_array($p['id'], $arraySelect[$module]))
                {
                    $return .= "<option value='$module-{$p['id']}'>{$p['name']}</option>";
                }
            }
        }
        
        return $return;
    }
    
    
    
    function createComponent($data, $selected)
    {
        $html = "
            <textarea id=\"link_id\" class=\"hidden\" name=\"link_id\">{$this->value}</textarea>                        
            <div class=\"columns\">                            
                <div class=\"six-columns\">
                    <p><strong>Страницы для выбора</strong> <a href=\"javascript:void(0);\" id=\"forward\" class=\"compact button\">выбрать все&nbsp;&nbsp;<span class=\"icon-forward\"></span></a></p>
                    <select id=\"selectValue\" name=\"selectValue\" class=\"full-width white-gradient multiple select\" size=\"15\">                                                                         
                        
                        $data
                        
                    </select>
                </div>
                <div class=\"six-columns\">                                
                    <p class=\"align-right\"><a href=\"javascript:void(0);\" id=\"backward\" class=\"compact button icon-backward\"> отменить все</a> <strong>Выбранные страницы</strong></p>                                                                                                    
                    <select id=\"selectSelected\" class=\"full-width multiple white-gradient select\" size=\"15\">                                                                                                            
                        
                        $selected
                        
                    </select>
                </div>
            </div>
        ";
        
        return $html;
    }
    
          
    
}
// END CI_SelectLink class

/* End of file SelectLink.php */
/* Location: ./modules/admin/libraries/SelectLink.php */
