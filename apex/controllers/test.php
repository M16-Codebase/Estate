<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 23.01.18
 * Time: 17:10
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class test extends MY_Controller
{
    function index()
    {
        $this->sendMessage('sdkfjhdksfjhsdkfhjsdkfjh');
    }

    function sendMessage ($message)
    {
        $message .= "<br><br>Время отправки: " . date('H:i:s d.m.Y') . "";
        try {
            $this->load->library('phpmailer'); //Класс phpmailer
            $this->phpmailer->ClearAllRecipients();
            // Кодировка
            $this->phpmailer->SMTPDebug = 1 ;
            $this->phpmailer->CharSet = "utf-8";
            //$this->phpmailer->SMTPDebug = 1;
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

            $this->phpmailer->AddAddress('pahuss@mail.ru' );
            $this->phpmailer->SetFrom('m16.noreplay@yandex.ru', 'M-16');

            $this->phpmailer->Subject = 'КРОН M16-ESTATE.RU | ';

            $this->phpmailer->ContentType = 'text/html';

            $this->phpmailer->Body = $message;
            $this->phpmailer->MsgHTML = $message;

            $r = $this->phpmailer->send();

        } catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            echo $e->getMessage(); //Boring error messages from anything else!
        }
        //$this->phpmailer->ErrorInfo;
        return $r;
    }
}
