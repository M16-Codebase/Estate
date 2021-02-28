<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * dygF9aGH password
 * Модуль: для тестирования разных разностей
 **/
ini_set('display_errors', 1);
class aatest extends MY_Controller {
    
    public function index() {
        
        //$this->dx_auth->forgot_password2('pahuss@mail.ru');
        
        /*
        $this->load->library('RusToEn', array(), 'rte');
        $res = $this->db->query("SELECT * FROM `ci_otzuv` WHERE 1")->result_array();
        
        $d = new DateTime();
        
        foreach ($res as $r) {
            $tst = $d->setTimestamp($r['date'])->format('d-m-Y');
            
            $n = $this->rte->translit($r['name']);
            
            $key = 'otzyv-klienta-' . $n . '-' . $tst;
            $r = $this->db->query("UPDATE `ci_otzuv` SET `otzuv_key`='{$key}' WHERE `id`={$r['id']}");
        }
        */
        
        
        /*SELECT * FROM `ci_rayon` WHERE `buildings_seotext``resale_seotext``residential_seotext``elite_seotext`
        `commercial_seotext``assignment_seotext`*/
        /*
        $st = [
            'buildings_seotext', 
            'resale_seotext' ,
            'residential_seotext',
             'elite_seotext' ,
            'commercial_seotext' ,
            'assignment_seotext'
        ];
        
        $link = 'href="http://m16-estate.ru';
        $nl = 'href="';
        
        foreach ($st as $s) {
            $res = $this->db->query("SELECT `{$s}`, `id` FROM `ci_metro` WHERE `{$s}` LIKE '%{$link}%'")->result_array();
        
            foreach ($res as $r) {
                //var_dump($r); 
                $text = str_replace($link, $nl, $r[$s]);
                //echo $text;
                
                
                $x = $this->db->query("UPDATE `ci_metro` SET `{$s}`='{$text}' WHERE `id` = {$r['id']}");
                //echo "UPDATE `ci_news` SET `text`={$text} WHERE `id` = {$r['id']}";
                var_dump($x);
                //exit;
            }
        }*/
        /*
        $res = $this->db->query("SELECT `text`, `id`, `link` FROM `ci_news` WHERE `text` LIKE '%{$link}%'")->result_array();
        
        foreach ($res as $r) {
            //var_dump($r); 
            $text = str_replace($link, $nl, $r['text']);
            //echo $text;
            
            
            $x = $this->db->query("UPDATE `ci_news` SET `text`='{$text}' WHERE `id` = {$r['id']}");
            //echo "UPDATE `ci_news` SET `text`={$text} WHERE `id` = {$r['id']}";
            //var_dump($x);
            //exit;
        }*/
        
        $res = $this->db->query("SELECT * FROM `ci_table_info` WHERE `table`='ci_otzuv'")->
            result_array();
        

        $content = array(
            'id' => '',
            'sort' => 'Порядок вывода',
            'banned' => 'Видимость',
            'name' => 'Автор',
            'email' => 'E-mail',
            'text' => 'Отзыв',
            'manager_name' => 'Имя менеджера',
            'tags' => 'Тэги',
            'date' => 'Дата',
            'photo_count' => '',
            'video_count' => '',
            'audio_count' => '',
            'comment_count' => '',
            'foto' => 'Фото',
            'video' => 'Видео',
            'audio' => 'Аудио',
            );
        $content = serialize($content);

        $comment_en = $content;

        $plasehold = array(
            'id' => '',
            'sort' => '',
            'banned' => '',
            'name' => '',
            'email' => '',
            'text' => '',
            'manager_name' => '',
            'tags' => '',
            'date' => '',
            'photo_count' => '',
            'video_count' => '',
            'audio_count' => '',
            'comment_count' => '',
            'foto' => '',
            'video' => '',
            'audio' => '',
            );
            
        $plasehold = serialize($plasehold);
        $new_type = $info = $plasehold;

        $field_type = array(
            'id' => array(
                'class' => '',
                'label_class' => '',
                'text_button' => '',
                'text_button_script' => '',
                'text_icon' => '',
                'select_chosen' => 'false',
                'switch_text' => '',
                'editor_function' => '',
                'img_path' => '',
                'width' => '',
                ),
            'sort' => array(
                'class' => '',
                'label_class' => '',
                'text_button' => '',
                'text_button_script' => '',
                'text_icon' => '',
                'select_chosen' => 'false',
                'switch_text' => '',
                'editor_function' => '',
                'img_path' => '',
                'width' => 'six',
                ),
            'banned' => array(
                'class' => 'revers',
                'label_class' => '',
                'text_button' => '',
                'text_button_script' => '',
                'text_icon' => '',
                'select_chosen' => 'false',
                'switch_text' => '',
                'editor_function' => '',
                'img_path' => '',
                'width' => 'six',
                ),
            'name' => array(
                'class' => '',
                'label_class' => '',
                'text_button' => '',
                'text_button_script' => '',
                'text_icon' => '',
                'select_chosen' => 'false',
                'switch_text' => '',
                'editor_function' => '',
                'img_path' => '',
                'width' => '',
                ),
            'email' => array(
                'class' => '',
                'label_class' => '',
                'text_button' => '',
                'text_button_script' => '',
                'text_icon' => '',
                'select_chosen' => 'false',
                'switch_text' => '',
                'editor_function' => '',
                'img_path' => '',
                'width' => '',
                ),
            'text' => array(
                'class' => '',
                'label_class' => '',
                'text_button' => '',
                'text_button_script' => '',
                'text_icon' => '',
                'select_chosen' => 'false',
                'switch_text' => '',
                'editor_function' => '',
                'img_path' => '',
                'width' => '',
                ),
            'manager_name' => array(
                'class' => '',
                'label_class' => '',
                'text_button' => '',
                'text_button_script' => '',
                'text_icon' => '',
                'select_chosen' => 'false',
                'switch_text' => '',
                'editor_function' => '',
                'img_path' => '',
                'width' => '',
                ),
            'tags' => array(
                'class' => 'tagsinput',
                'label_class' => '',
                'text_button' => '',
                'text_button_script' => '',
                'text_icon' => '',
                'select_chosen' => 'false',
                'switch_text' => '',
                'editor_function' => '',
                'img_path' => '',
                'width' => '',
                ),
            'date' => array(
                'class' => '',
                'label_class' => '',
                'text_button' => '',
                'text_button_script' => '',
                'text_icon' => '',
                'select_chosen' => 'false',
                'switch_text' => '',
                'editor_function' => '',
                'img_path' => '',
                'width' => '',
                ),
            'photo_count' => array(
                'class' => '',
                'label_class' => '',
                'text_button' => '',
                'text_button_script' => '',
                'text_icon' => '',
                'select_chosen' => 'false',
                'switch_text' => '',
                'editor_function' => '',
                'img_path' => '',
                'width' => '',
                ),
            'video_count' => array(
                'class' => '',
                'label_class' => '',
                'text_button' => '',
                'text_button_script' => '',
                'text_icon' => '',
                'select_chosen' => 'false',
                'switch_text' => '',
                'editor_function' => '',
                'img_path' => '',
                'width' => '',
                ),
            'audio_count' => array(
                'class' => '',
                'label_class' => '',
                'text_button' => '',
                'text_button_script' => '',
                'text_icon' => '',
                'select_chosen' => 'false',
                'switch_text' => '',
                'editor_function' => '',
                'img_path' => '',
                'width' => '',
                ),
            'comment_count' => array(
                'class' => '',
                'label_class' => '',
                'text_button' => '',
                'text_button_script' => '',
                'text_icon' => '',
                'select_chosen' => 'false',
                'switch_text' => '',
                'editor_function' => '',
                'img_path' => '',
                'width' => '',
                ),
            'foto' => array(
                'class' => '',
                'label_class' => '',
                'text_button' => '',
                'text_button_script' => '',
                'text_icon' => '',
                'select_chosen' => 'false',
                'switch_text' => '',
                'editor_function' => '',
                'img_path' => '',
                'width' => '',
                ),
            'video' => array(
                'class' => '',
                'label_class' => '',
                'text_button' => '',
                'text_button_script' => '',
                'text_icon' => '',
                'select_chosen' => 'false',
                'switch_text' => '',
                'editor_function' => '',
                'img_path' => '',
                'width' => '',
                ),
            'audio' => array(
                'class' => '',
                'label_class' => '',
                'text_button' => '',
                'text_button_script' => '',
                'text_icon' => '',
                'select_chosen' => 'false',
                'switch_text' => '',
                'editor_function' => '',
                'img_path' => '',
                'width' => '',
                ),
            );
            
            
            $field_type = serialize($field_type);
            
            $res = $this->db->query("UPDATE `ci_table_info` 
            SET `comment`='{$content}',`comment_en`='{$content}',
            `placeholder`='{$plasehold}',`info`='{$plasehold}',`new_type`='{$plasehold}',
            `fileld_type`='{$field_type}' 
            WHERE `table`='ci_otzuv'");
            
            var_dump($res);
        
    }
    
    
    
    
    
    
    /*
    function _encode($password)
	{
		$majorsalt = ''; //$this->config->item('DX_salt');
		
		$_pass = str_split($password);

		// encrypts every single letter of the password
		foreach ($_pass as $_hashpass) {
			$majorsalt .= md5($_hashpass);
		}

		// encrypts the string combinations of every single encrypted letter
		// and finally returns the encrypted password
		return md5($majorsalt);
	}*/
}


