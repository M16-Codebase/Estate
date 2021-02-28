<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Template functions
 *
 * Some of the functions use this
 */

function getUrl() {
    $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
    $url .= ( $_SERVER["SERVER_PORT"] != 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
    $url .= $_SERVER["REQUEST_URI"];
    return $url;
}

if ( ! function_exists('templ'))
{
    function templ()
    {
        $ci = &get_instance();
        
        $role_id = ses_data('DX_role_id');       
        $moduleName = uri(1); // определяем модуль        
        $add_del_remove = uri(4); // определяем находимся мы на странице добавления или редактирования
             
        $role = $ci->dx_auth->get_role_name(); // получаем роль текущего пользователя    
        if($role != 'super')
        {
            $ci->db->where('id', $role_id);
    	    $role_data = $ci->db->get('auth_roles');            
            $role_data = $role_data->row_array();            
            $perm = unserialize($role_data['permissions']);
            
            if(empty($add_del_remove)) // если мы находимся на странице списка
            {
                // проверяем есть ли хоть какой-то доступ к этому модулю
                if(count($perm[$moduleName]) == 0) // если массив пустой значит доступа к странице нет
                {
                    $deny = "<h2 class='thin with-padding blue boxed wrapped'>Ваши права на текущую страницу ограничены администрацией сайта.</h2>";                       
                        echo '
                            <script type="text/javascript">                			                                    
                                    $("#panel-content").empty().append("'.$deny.'");                     
                            </script>
                    ';
                }
                else // если какие-то доступы присутствуют, то вытаскиваем их, остальные удаляем
                {                    
                    $dsp_class = array(
                        'add'=>'sets_add',
                        'edit'=>'icon-pencil',
                        'del'=>'delRow, #actionSubmit, .checks, label[for=button-checkbox-all], select[name=action]',
                        'set'=>'icon-tools',
                        'lang'=>'icon-page-list'
                        ); // типы доступа
                    $dsp = array('add','edit','del','set','lang'); // типы доступа
                    foreach($perm[$moduleName] as $k=>$cls)
                    {                        
                        if(in_array($k,$dsp))
                        {
                            $keys = array_search($k,$dsp);
                            unset($dsp[$keys]);
                        }
                    }                    
                    
                    $ms = '';  
                    $coo = count($dsp);
                    $i = 1;
                    $ars = array();
                           
                    foreach($dsp as $k=>$d)
                    {                        
                        $ars[] = $d;
                        
                        if($i < $coo)
                        {                                                    
                            $ms .= '.'.$dsp_class[$d].', ';
                        }
                        else
                        {
                            $ms .= '.'.$dsp_class[$d];
                        }
                        $i++;
                    }                     
                        
                        if(in_array('set', $ars) and in_array('lang', $ars))
                        {
                            $ms .= ', .sets_setting';
                        }
                        
                        echo '
                            <script type="text/javascript">                			
                                    $("'.$ms.'").remove();                     
                            </script>
                        ';
                }
            }  
            else // иначе
            {
                // проверяем редактирование
                if($add_del_remove == 'edit')
                {
                    if(!isset($perm[$moduleName]['edit']))
                    {
                        $deny = "<h2 class='thin with-padding blue boxed wrapped'>Ваши права на текущую страницу ограничены администрацией сайта.</h2>";                       
                        echo '
                            <script type="text/javascript">                			
                                    $("#panel-content").empty().append("'.$deny.'");                     
                            </script>
                        ';
                    }
                }
                
                // проверяем добавление
                if($add_del_remove == 'add')
                {
                    if(!isset($perm[$moduleName]['add']))
                    {                        
                        $deny = "<h2 class='thin with-padding blue boxed wrapped'>Ваши права на текущую страницу ограничены администрацией сайта.</h2>";                       
                        echo '
                            <script type="text/javascript">                			
                                    $("#panel-content").empty().append("'.$deny.'");                     
                            </script>
                        ';
                    }
                }                
            }                    
        } 
            
        if($role_id == '3' and uri(3) == 'roles' and $role == 'admin' and uri(5) == 3)
        {
            echo '
                <script type="text/javascript">                			
                        $(".checks").attr("disabled",true);
                        $(".checks").attr("name","");
                        $(".hov, .noneCheck, .hovRow").removeClass("hov noneCheck hovRow");                      
                </script>
            ';                
        }       
    }

}
/* End of file helper.php */