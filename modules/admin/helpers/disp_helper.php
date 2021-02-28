<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * Хелпер вывода шаблона страниц админки и немного дополнительных функций
 * 
**/


  
/**
 ***********************************
 * Шаблон администратора
 ***********************************  
**/ 
/*
function admin_display($data=NULL)
{        
    $d = array();
        
    if(!is_array($data))
    {
        $d['data'] = $data;
    }
    else 
    {
        $d = $data;
    }
    
    $ci = &get_instance();
    
    $data['apex'] = $ci->data;
    
    $ci->load->model('admin/module_model','model');
    
    if(is_super())
    {
        $pr = $ci->model->all_data_where('admin_menu',array('banned'=>'0'),'sort');
    }
    else
    {    
        $pr = $ci->model->all_data_where('admin_menu',array('banned'=>'0','is_super'=>'0'),'sort');
                       
        $role_id = ses_data('DX_role_id'); 
        $ci->db->where('id', $role_id);
        $role_data = $ci->db->get('auth_roles');
        $role_data = $role_data->row_array();
        $perm = unserialize($role_data['permissions']);    
        $dsp = array();       
       
        if(!empty($perm))
        {
            foreach($perm as $p=>$b)
            {
                if(count($b) > 0)
                {
                    if($p != 'admin')
                    {
                        $dsp[] = $p;
                    }
                }
            }               
        }
            
        foreach($pr as $k=>$a)
        {
            if(!empty($a['link']))
            {
                $ex = explode('/',$a['link']);
                if(!in_array($ex[0],$dsp))
                {
                    unset($pr[$k]);
                }
            }
        }       

        foreach($pr as $k=>$a)
        {
            $prId[] = $a['parent_id'];
        }
        
        foreach($pr as $k=>$a)
        {
            if(empty($a['link']))
            {                
                if(!in_array($a['id'],$prId))
                {
                    unset($pr[$k]);
                }                            
            }
        }
        
    }
  
    $d['menu'] = li_tree(create_children($pr),'');
    $ci->load->view('admin/template/index',$d);    
}   
*/
    
?>