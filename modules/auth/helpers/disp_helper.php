<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * Хелпер вывода шаблона страниц админки и немного дополнительных функций
 * 
**/


  
/**
 ***********************************
 * Шаблон авторизации администратора
 ***********************************  
**/ 
function auth_display($data=NULL)
{
    $ci = &get_instance();
    return $ci->load->view('auth/auth/login_admin',$data);
}

function auth_display_assignments($data=NULL)
{
    $ci = &get_instance();
    return $ci->load->view('auth/auth/login_form',$data);
}

?>