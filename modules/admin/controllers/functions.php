<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Functions extends MY_Admin {	

    function __construct()
    {
        // конструктор
        parent::__construct();         
                                                                                                         
    } 
    

    /**
     * Редактирования определенных данных по типу: видимость, сортировка, title
    **/    
    function actionAjax()
    {
        $params = $this->input->post('params',true);        
        
        $id = $params['id'];
        $val = $params['val'];
        $table = $params['table'];
        $module = $params['module'];
        $action = $params['action'];
                
        $return = false;

        $this->load->model('module_model');

        if($action == 'switch')
        {
        	if($table == 'military') {
        		$table = 'buildings';
               	$where = array('military_banned'=>$val);
        	}

             elseif($table == 'commerce') {
                $table = 'buildings';
                $where = array('commerce_banned'=>$val);
            } 
             else {
           		$where = array('banned'=>$val);
		   	}
        }
        elseif($action == 'sort')
        {
            $where = array('sort'=>$val);
        } 
        elseif($action == 'title')
        {
            $where = array('title'=>$val);
        }
        elseif($action == 'military_sort')
        {
            $where = array('military_sort' => $val);
        }

        if($action == 'military_sort') {
            $table = 'buildings';
        }

        if($action == 'commerce_sort') {
            $table = 'buildings';
        }

        if($this->module_model->module_edit($id,$where,$table))
        {
            $return = true;

            if ($action == 'switch' AND $return AND $val == 1 AND $info = get_remove_text($id, $table))
            {
                remove_notify($info, 'Скрытие объекта');
            }
        }
        
        echo json_encode(array(
            'ok' => $return
        ));            
    }
    
    
    /**
     * Ajax поиск данных в списке таблицы
    **/
    function searchAjaxTable()
    {        
        $this->load->model('module_model');                        
        echo $this->admin_view('admin/template/list_table_pagination',$parametru,true);
    }
    
    
    /**
     * Техподдержка
    **/     
	function support()
	{		                                                                                                     
        if($this->input->is_ajax_request())
        {
            $data['mes'] = array(
                'color' => 'red',
                'text' => 'Error server message. Please try again later.'
            );
            
            $name = $this->input->post('name', true);
            $email = $this->input->post('email', true);
            $text = nl2br($this->input->post('text', true));
            
            $message = "
                <br /><br />
                <p style='font-weight: 700; font-size: 18px;'> Сообщение пришло с сайта: <a href='".site_url()."'>".site_url()."</a></p>
                <br /><br />
                <p style='font-weight: 700; font-size: 18px;'>Текст сообщения:</p><br />
                {$text}            
            ";            
                                                
            if($this->send_message('support@webapex.com.ua', $email, $name, 'Техническая поддержка(CMS Apex)', $message, 'Support WebApex'))
            {
                $data['mes'] = array(
                    'color' => 'green',
                    'text' => 'Message has been sent to techsupport <span style="font-weight: 700;">webapex.com.ua</span>'                            
                );
                
                $ok = 2;
            }
            
            echo json_encode(array(
                    'ok' => $ok,
                    'mes' => $this->admin_view('template/message', $data, true)
                 ));
        }
        else
        {        
            $this->data['data'] = $this->admin_view('template/support','',true);
    
            $this->admin_display($this->data);
        }            
	}
    
    
    /**
     * Генератор sitemap.xml
    **/     
    function sitemap($submit=NULL)
    {
       if(!$this->dx_auth->is_admin())
        die("<h2 class='thin with-padding blue boxed wrapped'>Ваши права на текущую страницу ограничены администрацией сайта.</h2>");
        
        if($submit != NULL)
        {
            require_once(MDPATH.'admin/libraries/Sitemap.php');
            
            // Создаём класс. 
            $sitemap = new Sitemap(); 
            
            // Добавим страничку 
            $sitemap->addItem(new SitemapItem( 
              site_url(), // URL. 
              date('Y-m-d'), // Время в формате timestamp. 
              SitemapItem::weekly, //Частота обновления (константы класса SitemapItem). 
              0.7 // Приоритет страницы. 
            )); 
            
            // Подключаем модель
            $this->load->model('menu/admin_menu_model','model');            
            $mas_page = $this->model->moduleLoad();
                                            
            // пустые модули
            foreach($mas_page as $key=>$item)
            {                                
                if($key != 'main')
                {
                    $pages[] = array(
                        'url' => $item
                    );
                }                                    
            } 
            
            // Добавим все остальные страницы сайта. 
            if(!empty($pages))
            {
                foreach($pages as $page){                    
                  $sitemap->addItem(new SitemapItem( 
                    site_url($page['url']),
                    date('Y-m-d'),             
                    SitemapItem::weekly 
                  ));                   
                }
            }                         
            
            // Сгенерим sitemap в файл sitemap.xml. 
            // Если файл не указать - generate вернёт строку. 
            $sitemap->generate('sitemap.xml');
                    
            $this->data['data'] = '<div class="with-padding"><h2 class="thin"><strong>sitemap.xml</strong> '.$this->alang->admin_8.'</h2>';
            $this->data['data'] .= '<h3 class="thin">'.$this->alang->admin_9.': <span class="orange">'.(count($pages)+1).'</span></h3>';
            $this->data['data'] .= '<br /> <a class="button" target="_blank" href="'.site_url("sitemap.xml").'">'.$this->alang->admin_10.'</a></div>';
        }
        else
        {
            $this->data['data'] = '<div class="with-padding"><h3 class="thin">'.$this->alang->admin_11.' <span class="red">sitemap.xml</span></h3> <a class="button" href="'.BASEURL."/admin/functions/sitemap/generate".'"> '.$this->alang->admin_12.'<span class="blue">sitemap.xml</span></a></div>';
        }  

        $this->admin_display($this->data);            
	}
    
    
    /**
     * Експорт в ексель
    **/     
	function excel()
	{		                                                                                                     
        //require BASEURL.'/modules/admin/libraries/php-excel.class.php';
        $this->load->helper('admin/php-excel');
        
        
        $this->db->join('auth_user_profile', 'auth_users.id = auth_user_profile.user_id');
        $query = $this->db->get('auth_users');
        $querys = $query->result_object();
        
        foreach($querys as $q)
        {
            $data[] = array(
                $q->name,
                $q->last_name,
                $q->username,
                $q->email   
            );
        }      
        
        // Шапка
        $header = array(
            array('Имя','Фамилия','Логин','Email')
        );
        
        // Данные
        /*$data = array(                
            array('Schwarz', 'Oliver'),
            array('Test', 'Peter')                
        );*/
              
        
        // generate file (constructor parameters are optional)
        $xls = new Excel_XML('UTF-8', false, 'My Test Sheet');
        $xls->addArray($header);
        $xls->addArray($data);
        $xls->generateXML('my-test');
	}
    
    function send_message($komu, $kto, $name_kto, $tema, $message, $names='')
    {    
        if(empty($names)) {$names = configs('apex_title');}
        $ci = &get_instance();
        //Иначе - отправляем письмо			
    	$ci->load->library('phpmailer'); //Класс phpmailer
    	
    	$ci->phpmailer->ClearAllRecipients();
        
        // Кодировка
        $ci->phpmailer->CharSet = "utf-8";
        
    	//Емайл получателя		
    	$ci->phpmailer->AddAddress($komu,$names); //Мой адрес
    
    	//От кого
    	$ci->phpmailer->From = $kto;
    	$ci->phpmailer->FromName = $name_kto;
    	//Тема
    	$ci->phpmailer->Subject = $tema;
    	//Тип
    	$ci->phpmailer->ContentType = 'text/html';
    	//Текст
    	$ci->phpmailer->Body = $message;
        $ci->phpmailer->MsgHTML = $message;        
        
        //$ci->phpmailer->
        
    	//Отправляем
    	return $ci->phpmailer->send();//) return true; else return false;				
    }
    
    
    function unzip()
    {        
        require_once(APPPATH.'libraries/pclzip.lib.php');
        $archive = new PclZip(APPPATH.'../asset/uploads/module/rapid.zip');
        if ($archive->extract(MDPATH) == 0) {
            die("Error : ".$archive->errorInfo(true));
        }else{
            echo 'ok';
        }
    }
}

/* End of file */

