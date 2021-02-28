<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends MY_Admin {	

    function __construct()
    {
        // конструктор
        parent::__construct();                                                                    
    } 


/** ---------------------------------------------------------------------------------------------------- **/ 

    
/**
 * Главная страница
**/     
	function index()
	{		                                                                             
        $this->load->helper('directory'); 
        $map = directory_map('asset/dumper/backup',1);
        $mas='';            
        foreach($map as $m)
        {
            if($m != '' and $m != 'index.html' and $m != 'dumper.cfg.php')
            {
                                     
                $mas[] = array(
                    'name' => $m,
                    'download' => '/admin/backup/download/'.$m                                  
                );            
            }
        }       
      
        if(!empty($mas))
        {
            rsort($mas);
        }
        $dat['sql'] = $mas;
        
        $this->data['data'] = $this->admin_view('import_bd',$dat,true);
        
        
        echo $this->admin_display($this->data);            
	}


/** ---------------------------------------------------------------------------------------------------- **/ 

    /**
     * Бэкап всей базы
    **/
    function export()
    {
        // Загружаем класс DB utility
        $this->load->dbutil();
        
        $prefs = array(                                 
                'format'      => 'txt',             // gzip, zip, txt                
                'add_drop'    => TRUE,              // позволяем дописать параметр DROP TABLE в бэкап 
                'add_insert'  => TRUE,              // позволяем дописать параметр INSERT в бэкап 
                'newline'     => "\n"               // Символ новой строки 
              );
        
        // Создаем бэкап текущей бд и ассоциируем его с переменной 
        if($backup = $this->dbutil->backup($prefs))
        {
            $this->data['data'] = '<h2 class="thin blue no-padding no-margin">Бекап успешно создан';
            
            $data = 'backup/';
            $data .= date('d-m-Y--H-m-s');            
            $data .= '.sql';
            
            if(write_file($data, $backup))
            {
                $this->data['data'] .= ' и записан в файл</h2>';
            }                        
        }
        
        $this->data['data'] .= '<br /><br /> <a href="/admin/backup" class="button icon-reply"> Назад</a>';

        echo $this->admin_display($this->data);
    }
    

/** ---------------------------------------------------------------------------------------------------- **/ 

    /**
     * Загрузка файла в дамп
    **/
    function import()
    {                             
        $config['upload_path'] = './dumper/backup/';
        $config['allowed_types'] = 'gz';
        $config['max_size']	= '30720'; // 30Мб
        
        $this->load->library('upload');
        $this->upload->initialize($config); 
        
        $userfile = $_FILES['userfile']['tmp_name'];
        
		if ( ! $this->upload->do_upload($userfile))
		{
			$this->session->set_userdata(array('error' => $this->upload->display_errors()));
            redirect('admin/backup');
		}
        else
        {
            $this->session->set_userdata(array('success' => $this->upload->data()));
            redirect('admin/backup');
        }	
 
    }


/** ---------------------------------------------------------------------------------------------------- **/ 

    /**
     * Скачать файл
    **/
    function download($file)
    {        
        $this->load->helper('download');
        
        $data = file_get_contents("asset/dumper/backup/".$file); // Считываем содержимое файла
        $name = $file;
        
        force_download($name, $data);
        
        redirect('/admin/backup');
    }    
}

/* End of file */

