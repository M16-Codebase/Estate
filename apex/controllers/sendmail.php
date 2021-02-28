<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sendmail extends MY_Controller {

    function __construct()
    {
        // конструктор
        parent::__construct();
    }

    function index()
    {
        $dt = $_POST['dt'];
        $but = $_POST['but'];


        $userName=strip_tags(trim($_POST['name']));
        $userPhone=strip_tags(trim($_POST['phone']));
        $status=strip_tags(trim($_POST['status']));
        $ok = false;

        //$dt[9] = str_replace('http:/m16-estate.ru/', 'http:/m16-estate.ru/',  $dt[9]);

        if ($status == 'email'){
        	$message="<b>Сообщение с сайта m16-estate.ru</b>";
        	$message.="<br />Имя - ".$userName;
            $message.="<br />Телефон - ".$userPhone;

            if($but == 'complex')
            {
                $message.="<br><br>
                    Комплекс: <a href='{$dt[9]}'>{$dt[8]}</a><br>
                    ID квартиры: {$dt[7]}<br>
                    Количество комнат: {$dt[1]}<br>
                ";
            }
            else
            {
                $message.="<br><br>
                    Название: <a href='{$dt[9]}'>{$dt[8]}</a><br>
                    Количество комнат: {$dt[0]}<br>
                    Изображение: <img src='http://m16-estate.ru{$dt[5]}' width='70' alt='' /><br>
                    Стоимость: {$dt[6]}<br>
                ";
            }



            $message .= "<br><br>
                Время отправки: ".date('H:i:s d.m.Y')."
               
            ";

            $this->load->library('phpmailer'); //Класс phpmailer

        	$this->phpmailer->ClearAllRecipients();

            // Кодировка
            $this->phpmailer->CharSet = "utf-8";
			
			$this->phpmailer->SMTPDebug = 1;
			$this->phpmailer->CharSet = 'UTF-8';
			$this->phpmailer->IsSMTP();
			$this->phpmailer->Host = 'smtp.yandex.ru';
			$this->phpmailer->Port = 25;
			$this->phpmailer->SMTPSecure = 'tls';
			$this->phpmailer->SMTPAuth = true;
			$this->phpmailer->Username = 'm16.noreplay@yandex.ru';
			$this->phpmailer->Password = 'Vfkfattd016';



            $config = &get_config(); // получаем конфиги
        	//Емайл получателя
        	$this->phpmailer->AddAddress($config['apex_email'],$config['apex_names']); //Мой адрес
            //$this->phpmailer->AddAddress('oleg@cakelabs.ru','Oleg');
			
			

        	//От кого
        	//$this->phpmailer->From = 'no-reply@m16-estate.ru';
        	//$this->phpmailer->FromName = 'M16';
			$this->phpmailer->SetFrom('m16.noreplay@yandex.ru', 'M-16');
        	//Тема
        	$this->phpmailer->Subject = 'Отправлена форма с M16-ESTATE.RU';
        	//Тип
        	$this->phpmailer->ContentType = 'text/html';
        	//Текст
        	$this->phpmailer->Body = $message;
            $this->phpmailer->MsgHTML = $message;

            /*mail('oleg@cakelabs.ru', "Отправлена форма с M16-ESTATE.RU", $message,
                "From: no-reply@m16-estate.ru\r\n"
                ."Reply-To: no-reply@m16-estate.ru\r\n"
                ."X-Mailer: PHP/" . phpversion());*/

        	//Отправляем
        	$ok = $this->phpmailer->send(); // return true; else return false;
        }

        echo json_encode(array('ok'=>$ok));
    }
}
?>