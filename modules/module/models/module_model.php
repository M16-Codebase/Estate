<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module_model extends CI_Model
{
    // конструктор
    public function __construct()
    {
        parent::__construct();
    }    

    
    /**
     * Удаление данных о таблице в Информационной таблице
    **/
    public function drop_tableInfo($module_name)
    {
        $module_name = $this->db->dbprefix($module_name);        
        $this->db->delete('table_info',array('table'=>$module_name),'1');
    }
    

            
    /**
     * Удаление таблицы с БД
    **/
    public function drop_table($table_name)
    {                
        if($this->db->table_exists($table_name))
        {
            $sql = "DROP TABLE `".$this->db->dbprefix($table_name)."`";
            
            if($this->db->query($sql))
            {
                $this->db->where('link',$table_name);        
                $this->db->delete('module');
                return true;
            } 
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }   
    
    
    
    
    /**
     * Сохранение настроек
    **/
    public function module_config($data,$write)
    {    
        $set[] = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n";            
        
        $set[] = "/**";
        $set[] = "* Конфигурационный файл модуля | {$data[0]['name']}";
        $set[] = "**/\n\n"; 
        
        foreach($data as $t) 
        {   
            if($t['value'] == 'false' or $t['value'] == 'true' or is_numeric($t['value']))
            {
                $t['value'] = $t['value'];
            }
            else
            {
                $t['value'] = "{$t['value']}";
            }
            
            $mn = '';
                        
            if($t['name'] == 'module_name') { $mn = $t['value']; }
            
            $vls = htmlspecialchars($t['value']);
            
            $set[] = "\$config['{$t['name']}'] = '{$vls}';";
        }                
        if($mn == '')
        {
            $set[2] = "* Конфигурационный файл системы CMS Apex";
        }
        else
        {
            $set[2] = "* Конфигурационный файл модуля | {$mn}";
        }
        $output = implode("\n", $set);
    
        if(write_file(MDPATH . $write, $output))
            return true;
        return false;
    }    
    
    
    /**
     * Сохранение настроек
    **/
    public function module_language($data,$write)
    {    
        $set[] = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n";    
        
        $set[] = "/**";
        $set[] = "* Языковый файл модуля";
        $set[] = "**/\n\n"; 
        
        foreach($data as $key=>$t) 
        {            
            $vls = htmlspecialchars($t['name']);
            
            $set[] = "\$lang['{$key}'] = array(";
            $set[] = "\t\t'name' => \"{$vls}\",";
            $set[] = "\t\t'title' => '{$t['title']}'";
            $set[] = "\t);";
        }                
        
        $output = implode("\n", $set);
    
        if(write_file(MDPATH . $write, $output))
            return true;
        return false;
    } 
                                                
                
}
?>