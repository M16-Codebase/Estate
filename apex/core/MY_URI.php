<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/** MY_Controller **/
class MY_URI extends CI_URI 
{
    /**
	 * Set the URI String
	 *
	 * @access	public
	 * @param 	string
	 * @return	string
	 */
	function _set_uri_string($str)
	{
	    // подключаем массив языков        
		include(MDPATH.'admin/config/language.php');               
        
        // разбиваем адрес на массив
        $s = explode('/',$str);
        
        // заносим в переменную первую запись строки в надежде что это язык
        $l = $s[0];
        
        // если язык совпадает то
        if(array_key_exists($l, $lang))
        {                                        
            unset($s[0]); // удаляем его из адресной строки                                                
            
            $this->config->set_item('language', $lang[$l]); // задаем язык
            $this->config->set_item('language_prefix', $l); // префикс языка        
        }
        
        // слаживаем новую адресную строку
        $str = implode('/',$s);
                        
        // Filter out control characters
		$str = remove_invisible_characters($str, FALSE);

		// If the URI contains only a slash we'll kill it
		$this->uri_string = ($str == '/') ? '' : $str;
	}
}
/** # MY_URI **/