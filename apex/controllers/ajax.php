<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MY_Controller {

	function __construct()
	{
		// конструктор
		parent::__construct();
	}

	function ajax_calc()
	{
		$suma = $this->input->post('c1', true);
		$dohod = $this->input->post('c2', true);
		$i = -1;
		$year = 0;
		$percent = $this->input->post('c3', true) / 100;

		if(!empty($suma) and !empty($dohod))
		{
			while($year <= $suma)
			{
				$i++;
				if(($i%12) === 0)
				{
					if($i !== 0 )
					{
						//echo '<br><br>'.$i.'<br><br>';
						$dohod += $dohod * $percent;
					}
				}
				$year += $dohod;
				//echo intval($year).'<br>';
			}
		}

		$ye = substr($i/12, 0, 3);
		if (substr(trim($ye ), -1) == '.'){
			str_replace('.', '', $ye);
		}

		echo json_encode(array(
			'year' => '<span>'.$ye.'</span>'.morph($ye, ' год', ' года', ' лет'),
			'dohod' => '('.number_format(intval($year),0,',',' ').' руб.)'
		));
	}

	function ajax_calc2()
	{
		$suma = $this->input->post('c1', true); // сума кредите
		$vznos = $this->input->post('c2', true); // первоначальный взнос
		$stavka = $this->input->post('c3', true); // годовая ставка (%)
		$year = $this->input->post('c4', true); // кол-во месяцев кредита

		$ajxSuma = $suma - $vznos; // Кредит на сумму
		$st = ($stavka / 100) / 12;
		$pl = $ajxSuma * $st / (1-1/pow((1+$st),$year));
		$pkr = str_replace ('-','', $pl * $year - $ajxSuma);

		echo json_encode(array(
			'suma' => number_format($ajxSuma, 0, ' ', ' '),
			'stavka' => $stavka.'%', // По ставке % годовых
			'srok' => $stavka.morph($stavka, ' год', ' года', ' год'), // На срок
			'platezh' => number_format(ceil($pl), 0, ' ', ' '),
			'pkr' => number_format($pkr, 0, ' ', ' '),
			's' => number_format($ajxSuma + $pkr, 0, ' ', ' ')
		));
	}

	function ajax_calc_anuit(){
		$suma = $this->input->post('c1', true); // сума кредите
		$stavka = $this->input->post('c3', true); // годовая ставка (%)
		$year = $this->input->post('c4', true); // кол-во месяцев кредита
		$st = ($stavka / 100) / 12;
		$summ = $suma;
		$pl = $suma * ($st +  $st / (pow((1+$st),$year)-1));
		$forMonth = round($pl, 2, PHP_ROUND_HALF_UP);
		$total = number_format($forMonth*$year, 2, ',', ' ');
		$diff = 0;
		$res = array();
		$res['total'] = $total;
		$res['items'] = array();
		for ($i = 1; $i <= $year; $i++){
			$summ = $summ - $diff;
			//echo $summ." - ";
			$proc = round($summ*$st, 2, PHP_ROUND_HALF_UP);
			$dolg = round($forMonth - $proc, 2, PHP_ROUND_HALF_UP);
			$diff = $dolg;
			$res['items'][$i] = array('month'=>$i, 'dolg' => $summ, 'proc' => $proc, 'ssud' => $dolg, 'formonth' => $forMonth);
			//$summ = $summ - $forMonth;
			//echo $summ."<br>";
		}
		echo json_encode($res);


	}

	function favorites()
	{
		$this->load->helper('cookie');
		$id = $this->input->post('id', true);
		$category = $this->input->post('category', true);
		$action = $this->input->post('action', true);
		$favorites = $this->input->cookie('favorites', true);
		$favorites = explode('.',$favorites);
		$this->db->select('*');
		$this->db->where('object_id', $id);
		$this->db->where('category', $category);
		$quer = $this->db->get('favorites');
		$row = $quer->row_array();
		$idr = $row['id'];
		$favorite = array();
		foreach ($favorites as $k => $v){
			if (!empty($v))
				$favorite[$v] = $v;
		}

		if ($action == 'add'){
			if (empty($favorite)){
				$favorite[$idr] = $idr;
				$cookie = array(
					'name'   => 'favorites',
					'value'  => implode('.',$favorite),
					'expire' => time()+86500
				);
				$this->input->set_cookie($cookie);
			}
			else {
				$favorite[$idr] = $idr;
				$cookie = array(
					'name'   => 'favorites',
					'value'  => implode('.',$favorite),
					'expire' => time()+86500
				);
				$this->input->set_cookie($cookie);
			}
			$row['link'] = BASEURL.'/'.$row['category'].'/'.$row['link'];
			$row['foto'] = image($row['foto'], "small");
		}
		elseif ($action == 'delete'){
			unset($favorite[$idr]);
			$cookie = array(
				'name'   => 'favorites',
				'value'  => implode('.',$favorite),
				'expire' => time()+86500
			);
			$this->input->set_cookie($cookie);
		}

		$ok = 0;
		$list = '';

		/*if($action == 'add')
		{
			$favorite[$id] = $id;
			$ok = count($favorite);
		}
		elseif($action == 'remove')
		{
			unset($favorite[$id]);
			$ok = count($favorite);
		}
		elseif($action == 'count')
		{
			$ok = count($favorite);
		}
		elseif($action == 'list')
		{
			$list = $this->load->module('room')->list_favorite();
			$ok = count($favorite);
		}

		if(empty($favorite)) { $favorite = ''; $ok = 0; }

		$this->session->set_userdata('favorite', $favorite);

		echo json_encode(array('ok'=>$ok, 'list'=>$list, 'fv' => $favorite));*/
		echo json_encode(array('ok'=>$action, 'id'=>$idr, 'elem'=>$row));
	}






	function sendmail()
	{
		$email = $this->input->post('email', true);
		$name = $this->input->post('name', true);
		$phone = $this->input->post('phone', true);
		$message = nl2br($this->input->post('message', true));

		$ok = false;

		$template = '
			<style> a { color: #c51230; } a:hover { color: #f68f1f; } </style>
			<div style="width: 100%; margin: 0; padding: 0;  color: #000; font-size:15px; line-height: 135%; font-family: \'Arial\'">
			<div style="width: 600px; margin: 0 auto; ">
				Телефон: <br />
				'.$phone.'
				<br /><br />
				Сообщение: <br />
				'.$message.'	
			</div>        
			</div>
		';

		// массив с данными для отправки письма
		$send = array(
			'name' => $name,
			'email' => $email,
			'tema' => 'Обратная связь',
			'message' => $template
		);

		$this->load->library('phpmailer'); // Класс phpmailer

		// Очистка отправителей и получателей
		$this->phpmailer->ClearAllRecipients();
		$this->phpmailer->ClearAddresses();

		$this->phpmailer->CharSet = "utf-8"; // Кодировка  
		$this->phpmailer->ContentType = 'text/html'; //Тип письма			
		$this->phpmailer->Subject = $send['tema']; // Тема

		$this->phpmailer->Body = $send['message']; // Сообщение
		$this->phpmailer->MsgHTML = $send['message']; // Сообщение

		$this->phpmailer->AddAddress(config_item('apex_email'), config_item('apex_names')); // Получатель
		// Отправитель
		$this->phpmailer->From = $send['email'];
		$this->phpmailer->FromName = $send['name'];

		$ok = $this->phpmailer->send();

		echo json_encode(array(
			'ok' => $ok
		));
	}




	function ajaxMailchimp()
	{
		$ok = false;
		$email = $this->input->post('email', true);
		if(mailchimp('Подписчик', $email))
		{
			$ok = true;
		}

		echo json_encode(array('ok' => $ok));
	}

	function getBuildings()
	{
		$q = $this->input->get('q', true);
		$this->db->select('name');
		$this->db->like('razdelu', 0);
		$this->db->where('banned', 0);
		$this->db->where("(`name` LIKE '%".$q."%' OR `alias_name` LIKE '%".$q."%')");
		$query = $this->db->get('buildings')->result_array();
		$data = array();
		foreach ($query as $key => $value) {
			$data[] = $value["name"];
		}
		echo json_encode($data);
	}

	function getBuildingsNov()
	{
		$q = $this->input->get('q', true);
		$this->db->select('name');
		$this->db->like('razdelu', 0);
		$this->db->like('razdelu', 3);
		$this->db->where('banned', 0);
		$this->db->where("(`name` LIKE '%".$q."%' OR `alias_name` LIKE '%".$q."%')");
		$query = $this->db->get('buildings')->result_array();
		$data = array();
		foreach ($query as $key => $value) {
			$data[] = $value["name"];
		}
		echo json_encode($data);
	}

	function getBuildingsExcl()
	{
		$q = $this->input->get('q', true);
		$this->db->select('name');
		$this->db->like('razdelu', 0);
		$this->db->like('razdelu', 9);
		$this->db->where('banned', 0);
		$this->db->where("(`name` LIKE '%".$q."%' OR `alias_name` LIKE '%".$q."%')");
		$query = $this->db->get('buildings')->result_array();
		$data = array();
		foreach ($query as $key => $value) {
			$data[] = $value["name"];
		}
		echo json_encode($data);
	}

	function getBuildingsAssign()
	{
		$q = $this->input->get('q', true);
		$this->db->select('name');
		$this->db->like('razdelu', 8);
		$this->db->where('banned', 0);
		$this->db->where("(`name` LIKE '%".$q."%' OR `alias_name` LIKE '%".$q."%')");
		$query = $this->db->get('buildings')->result_array();
		$data = array();
		foreach ($query as $key => $value) {
			$data[] = $value["name"];
		}

		echo json_encode($data);
	}

	function getBuildingsResid()
	{
		$q = $this->input->get('q', true);
		$this->db->select('name');
		$this->db->distinct('name');
		$this->db->like('razdelu', 2);
		$this->db->where('banned', 0);
		$this->db->where("(`name` LIKE '%".$q."%' OR `alias_name` LIKE '%".$q."%')");
		$query = $this->db->get('buildings')->result_array();
		$data = array();
		foreach ($query as $key => $value) {
			$data[] = $value["name"];
		}
		echo json_encode($data);
	}

	function getAddress()
	{
		$q = $this->input->get('q', true);
		$name = $this->input->get('name', true);
		$cat = 1;
		if($name == 'elite')
			$cat = 3;
		$this->db->select('name');
		$this->db->like('razdelu', $cat);
		$this->db->where('banned', 0);
		$this->db->where("(`adress` LIKE '%".$q."%' OR `name` LIKE '%".$q."%')");
		$query = $this->db->get('buildings')->result_array();
		$data = array();
		foreach ($query as $key => $value) {
			$data[] = $value["name"];
		}
		echo json_encode($data);
	}

	function ajax_otzuv()
	{
		$name = $this->input->post('c1', true); // имя
		$email = $this->input->post('c2', true); // email
		$text = nl2br($this->input->post('c3', true)); // текст собщения
        $manager = nl2br($this->input->post('manager', true)); // manager name
		
		
		$this->load->library('RusToEn', array(), 'rte');
        $dt = new DateTime();
        
        $date = $dt->format('d-m-y');
        $n = $this->rte->translit($name);
        $key = 'otzyv-klienta-' . $n . '-' . $date;

		$ok = $this->db->insert('otzuv', array(
			'name' => $name,
            'otzuv_key' => $key,
			'email' => $email,
			'text' => $text,
            'manager_name' => $manager,
			'banned' => '1',
			'date' => time()
		));
		
		$insId = $this->db->insert_id(); 
        $uri = $_SERVER['REQUEST_SCHEME'] . '://' . "m16-estate.ru/otzuv/admin/index/edit/{$insId}";
              
        if ($ok) {
            $message = "<b> Сообщение с сайта m16-estate.ru</b>";
            $message .= "<br /> Поступил новый отзыв";
            $message .= "<br /><br />Ссылка редактирование - <a href='" . $uri . "'>" . $uri . "</a>";
            $message .= "<br><br>Время отправки: " . date('H:i:s d.m.Y') . "";
            
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
            $this->phpmailer->AddAddress('bazhenov@m16.bz');
			$this->phpmailer->AddAddress('gds@m16.bz');
            $this->phpmailer->SetFrom('m16.noreplay@yandex.ru', 'M-16');
            //Тема
            $this->phpmailer->Subject = 'Вопрос с M16-ESTATE.RU | ' . $theme;
            //Тип
            $this->phpmailer->ContentType = 'text/html';
            //Текст
            $this->phpmailer->Body = $message;
            $this->phpmailer->MsgHTML = $message;
            $this->phpmailer->send();
        }
		echo json_encode(array('ok' => $ok));
	}

	function ajax_mupload()
	{
		$config['upload_path'] = './asset/uploads/images/buildings';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		$response = array();
		foreach ($_FILES as $k => $v){
			$image = $v;
			$path_info = substr($image['name'], strrpos($image['name'], '.', -1)+1);
			$name = md5(time().$image['tmp_name']).'.'.$path_info;
			if ($this->upload->do_upload($k)){
				$up = $this->upload->data();
				$response[$k] = $up;
				$response[$k]['preview_name'] = image_resize('/asset/uploads/images/buildings/'.$up['file_name'], 'preview', false);
				$response[$k]['error'] = 0;
			}
			else {
				$response[$k]['error'] = 1;
			}
		}
		echo json_encode($response);
	}

	function ajax_rmupload()
	{
		$config['upload_path'] = './asset/uploads/reviews/temp';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		$response = array();
		foreach ($_FILES as $k => $v){
			$image = $v;
			$path_info = substr($image['name'], strrpos($image['name'], '.', -1)+1);
			$name = md5(time().$image['tmp_name']).'.'.$path_info;
			if ($this->upload->do_upload($k)){
				$up = $this->upload->data();
				$response[$k] = $up;
				$response[$k]['preview_name'] = image_resize('/asset/uploads/reviews/temp/'.$up['file_name'], 'preview', false);
				$response[$k]['error'] = 0;
			}
			else {
				$response[$k]['error'] = 1;
			}
		}
		echo json_encode($response);
	}

	function ajax_audioupload()
	{
		$config['upload_path'] = './asset/uploads/reviews/temp';
		$config['allowed_types'] = 'mp3|wav';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		$response = array();
		foreach ($_FILES as $k => $v){
			$image = $v;
			$path_info = substr($image['name'], strrpos($image['name'], '.', -1)+1);
			$name = md5(time().$image['tmp_name']).'.'.$path_info;
			if ($this->upload->do_upload($k)){
				$up = $this->upload->data();
				$model = 'admin_files_table_model';
				$this->load->model('files_table/'.$model, $model);
				$mass = array();
				$path = explode('m16-estate.ru', $up['file_path']);
				$mass = array('name'=>$up['file_name'],
								'orig_name'=>$up['client_name'],
								'type'=>$up['file_type'],
								'ext'=>$up['file_ext'],
								'size'=>$up['file_size'],
								'path'=>$path[0],
								'date_add'=>time()
				);
				$this->$model->module_add($mass);
				$id = $this->db->insert_id();
				$response[$k] = $up;
				$response[$k]['id'] = $id;
				//$response[$k]['preview_name'] = image_resize('/asset/uploads/reviews/temp/'.$up['file_name'], 'preview', false);
				$response[$k]['error'] = 0;

			}
			else {
				$response[$k]['error'] = 1;
			}
		}

		echo json_encode($response);
	}

	function ajax_oupload()
	{
		$config['upload_path'] = './asset/uploads/images/buildings';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		$response = array();
		foreach ($_FILES as $k => $v){
			$image = $v;
			$path_info = substr($image['name'], strrpos($image['name'], '.', -1)+1);
			$name = md5(time().$image['tmp_name']).'.'.$path_info;
			if ($this->upload->do_upload($k)){
				$up = $this->upload->data();
				$response['data'] = $up;
				$response['data']['preview_name'] = image_resize('/asset/uploads/images/buildings/'.$up['file_name'], 'preview', false);
				$response['error'] = 0;
			}
			else {
				$response['error'] = 1;
			}
		}
		echo json_encode($response);
	}

	function ajax_noupload()
	{
		$config['upload_path'] = './asset/uploads/images/news';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		$response = array();
		foreach ($_FILES as $k => $v){
			$image = $v;
			$path_info = substr($image['name'], strrpos($image['name'], '.', -1)+1);
			$name = md5(time().$image['tmp_name']).'.'.$path_info;
			if ($this->upload->do_upload($k)){
				$up = $this->upload->data();
				$response['data'] = $up;
				$response['data']['preview_name'] = image_resize('/asset/uploads/images/news/'.$up['file_name'], 'preview', false);
				$response['error'] = 0;
			}
			else {
				$response['error'] = 1;
			}
		}
		echo json_encode($response);
	}

	function ajax_poupload()
	{
		$config['upload_path'] = './asset/uploads/images/partners';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		$response = array();
		foreach ($_FILES as $k => $v){
			$image = $v;
			$path_info = substr($image['name'], strrpos($image['name'], '.', -1)+1);
			$name = md5(time().$image['tmp_name']).'.'.$path_info;
			if ($this->upload->do_upload($k)){
				$up = $this->upload->data();
				$response['data'] = $up;
				$response['data']['preview_name'] = image_resize('/asset/uploads/images/partners/'.$up['file_name'], 'preview', false);
				$response['error'] = 0;
			}
			else {
				$response['error'] = 1;
			}
		}
		echo json_encode($response);
	}

	function ajax_presupload()
	{
		$config['upload_path'] = './asset/uploads/presentations';
		$config['allowed_types'] = 'pdf';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);

		$response = array();
		foreach ($_FILES as $k => $v){

			$image = $v;

			$path_info = substr($image['name'], strrpos($image['name'], '.', -1)+1);
			$name = md5(time().$image['tmp_name']).'.'.$path_info;
			if ($this->upload->do_upload($k)){
				$up = $this->upload->data();
				$response['data'] = $up;
				$response['error'] = 0;
			}
			else {
				$response['error'] = 1;
			}
		}
		echo json_encode($response);
	}

	function ajax_supload()
	{
		$config['upload_path'] = './asset/uploads/images/slider';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		$response = array();
		foreach ($_FILES as $k => $v){
			$image = $v;
			$path_info = substr($image['name'], strrpos($image['name'], '.', -1)+1);
			$name = md5(time().$image['tmp_name']).'.'.$path_info;
			if ($this->upload->do_upload($k)){
				$up = $this->upload->data();
				$response['data'] = $up;
				$response['data']['preview_name'] = image_resize('/asset/uploads/images/slider/'.$up['file_name'], 'preview', false);
				$response['error'] = 0;
			}
			else {
				$response['error'] = 1;
			}
		}
		echo json_encode($response);
	}

	function excursion()
	{

		$userName=strip_tags(trim($_POST['name']));
		$userPhone=strip_tags(trim($_POST['phone']));
		$bron=strip_tags(trim($_POST['bron']));
		$email=strip_tags(trim($_POST['email']));
		$ip = $this->get_ip();
		$minfo = $this->get_ip_info($ip);
		$ok = false;

		$message="<b>Сообщение с сайта m16-estate.ru</b>";
		$message.="<br />Имя - ".$userName;
		$message.="<br />Телефон - ".$userPhone;
		$message.="<br />Email - ".$email;
		$message.="<br />К-во мест брони - ".$bron;
		$message.="<br />IP: ".$ip;
		$message.="<br />Местоположение: ".$minfo['city'].', '.$minfo['region'].', '.$minfo['district'];
		$message .= "<br><br>Время отправки: ".date('H:i:s d.m.Y')."";

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
		//$this->phpmailer->AddAddress('oleg@cakelabs.ru',$config['apex_names']);
		$this->phpmailer->SetFrom('m16.noreplay@yandex.ru', 'M-16');
		//Тема
		$this->phpmailer->Subject = 'Заявка на экскурсию с M16-ESTATE.RU';
		//Тип
		$this->phpmailer->ContentType = 'text/html';
		//Текст
		$this->phpmailer->Body = $message;
		$this->phpmailer->MsgHTML = $message;
		$ok = $this->phpmailer->send(); // return true; else return false;

		echo json_encode(array('ok'=>$ok));
	}

	function askSend()
	{
		/*
		//global $h;
		$userName=strip_tags(trim($_POST['name']));
		$userPhone=strip_tags(trim($_POST['phone']));
		$betterCall=strip_tags(trim($_POST['better_call']));
		$betterCallTo=strip_tags(trim($_POST['better_call_to']));
		$email=strip_tags(trim($_POST['email']));
		$comment=strip_tags(trim($_POST['comment']));
		$uri=strip_tags(trim($_POST['uri']));
		$theme=strip_tags(trim($_POST['theme']));
		$ip = $this->get_ip();
		
		// от инфо отказываемся, т.к. очень замедляет ответ,
        // это неприемлемо
        // $minfo = $this->get_ip_info($ip);
		
		$ok = false;
		//$info = $this->get_ip_info($ip);
		//print_r($info);
		$message="<b>Сообщение с сайта m16-estate.ru</b>";
		$message.="<br />Имя - ".$userName;
		$message.="<br />Телефон - ".$userPhone;
		$message.="<br />Email - ".$email;
		$message.="<br />Время для звонка - ".$betterCall." - ".$betterCallTo;
		$message.="<br />Комментарий - ".$comment;
		$message.="<br /><br />Ссылка на страницу - <a href='".$uri."'>".$uri."</a>";
		$message.="<br />IP: ".$ip;
		
		if ($minfo) {
            $message .= "<br />Местоположение: " . $minfo['city'] . ', ' . $minfo['region'] .
                ', ' . $minfo['district'];
        }

		$message .= "<br><br>Время отправки: ".date('H:i:s d.m.Y')."";

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
		$this->phpmailer->AddAddress('m16.order@yandex.ru',$config['apex_names']); //Мой адрес
		//$this->phpmailer->AddAddress('pahuss@mail.ru',$config['apex_names']);
		$this->phpmailer->SetFrom('m16.noreplay@yandex.ru', 'M-16');
		//Тема
		$this->phpmailer->Subject = 'Вопрос с M16-ESTATE.RU | '.$theme;
		//Тип
		$this->phpmailer->ContentType = 'text/html';
		//Текст
		$this->phpmailer->Body = $message;
		$this->phpmailer->MsgHTML = $message;
		
		$ok = $this->phpmailer->send(); // return true; else return false;
		*/
		$ok=true;
		echo json_encode(array('ok'=>$ok));
	}

	function getUrl() {
		$url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
		$url .= ( $_SERVER["SERVER_PORT"] != 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
		$url .= $_SERVER["REQUEST_URI"];
		return $url;
	}

	function get_ip()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}


	function get_ip_info($ip)
	{
		$fgc = file_get_contents('http://ipgeobase.ru:7020/geo/?ip='.$ip);
		//$fgc =  mb_convert_encoding($fgc, 'UTF-8', 'windows-1251');

		$xml = new SimpleXMLElement($fgc);

		$GeoIP = array(
			'city' => $xml->ip->city,
			'region' => $xml->ip->region,
			'district' => $xml->ip->district,
			'lat' => $xml->ip->lat,
			'lng' => $xml->ip->lng,
		);

		return $GeoIP;

	}

	/**
	 * Convert string to requested character encoding.
	 * This function performs a character set conversion on the string str from in_charset to out_charset.
	 *
	 * @param string $str
	 * @param string $in_charset
	 * @param string $out_charset
	 * @return string|false results of {@link _read_cache_file()}
	 */
	function ln_str_encode($str, $in_charset, $out_charset)
	{

		if (function_exists('mb_convert_encoding'))
		{
			return mb_convert_encoding($str, $out_charset, $in_charset);
		}
		else
		{
			return iconv($in_charset, $out_charset, $str);
		}



	}

	function like(){
		$id = (int)$this->input->post('id', true);
		$identity = $this->input->post('identity', true);
		$ip = $this->get_ip();
		if($id != 0)
		{
			$this->db->where('item_id', $id);
			$this->db->where('identity', $identity);
			$this->db->where('ip', $ip);
		}
		$query = $this->db->get('likes');
		$c = $query->result_array();
		if (count($c) == 0){
			$this->db->insert('likes', array('item_id'=>$id, 'identity'=>$identity, 'ip'=>$ip));
		}
		$this->db->where('item_id', $id);
		$this->db->where('identity', $identity);
		$query = $this->db->get('likes');
		$c = $query->result_array();
		echo count($c);
	}

	function vote(){
		$id = (int)$this->input->post('id', true);
		$vote_id = $this->input->post('vote_id', true);
		$ip = $this->get_ip();
		if($id != 0)
		{
			$this->db->where('vote_id', $vote_id);
			//$this->db->where('answer_id', $id);
			$this->db->where('ip', $ip);
		}
		$query = $this->db->get('votes_count');
		$c = $query->result_array();
		if (count($c) == 0){
			$this->db->insert('votes_count', array('vote_id'=>$vote_id, 'answer_id'=>$id, 'ip'=>$ip));
			$this->db->where('id', $id);
			$this->db->set('count', 'count+1', FALSE);
			$this->db->update('votes_answers');
		}
		$this->db->where('vote_id', $vote_id);
		$this->db->order_by('count', 'DESC');
		$query = $this->db->get('votes_answers');
		$c = $query->result_array();
		echo json_encode($c);
	}


	function send_request()
	{
		$data = array();
		$text = array();
		
		// Подготавливаем массив
		foreach ($_POST['filter'] as $row)
			$data[$row['name']] = $row['value'];

		if (isset($data['price_from'], $data['price_to']))
			$text['price'] = '<strong>Цена:</strong>'."\r\t".'от '.$data['price_from'].' до '.$data['price_to'].' млн.';

		if (isset($data['square_from'], $data['square_to']))
			$text['square'] = '<strong>Площадь:</strong>'."\r\t".'от '.$data['square_from'].' до '.$data['square_to'].' м2.';

		// Район
		if ($data['rayon'])
		{
			$this->db->where_in('id', explode(',', $data['rayon']));

			$rayon = array();

			foreach ($this->db->get('rayon')->result_array() as $row)
				$rayon[] = $row['name'];

			if (count($rayon))
				$text['rayon'] = "<strong>Районы:</strong><br />\t".implode("<br />\t", $rayon);
		}

		// Метро
		if ($data['metro'])
		{
			$metro_array = explode(',', $data['metro']);
			if(in_array(46,$metro_array)) $metro_array[] = 44;

			$this->db->where_in('id', $metro_array);

			$metro = array();

			foreach ($this->db->get('metro')->result_array() as $row)
				$metro[] = $row['name'];

			if (count($metro))
				$text['metro'] = "<strong>Метро:</strong><br />\t".implode("<br />\t", $metro);
		}

		// Поиск
		if ($data['name'])
		{
			$text['metro'] = "<strong>Поиск:</strong><br />\t".$data['name'];
		}

		if ($data['srok'])
		{
			$srok = array();

			foreach (explode(',', $data['srok']) as $value)
				$srok[] = ($value == 1)?'Сдан':$value;

			if (count($srok))
				$text['srok'] = "<strong>Срок:</strong><br />\t".implode("<br />\t", $srok);
		}

		// Застройщик
		if ($data['builder'])
		{
			$this->db->where_in('id', explode(',', $data['builder']));

			$builder = array();

			foreach ($this->db->get('builders')->result_array() as $row)
				$builder[] = $row['name'];

			if (count($builder))
				$text['builder'] = "<strong>Застройщик:</strong><br />\t".implode("<br />\t", $builder);
		}

		// Класс жилья
		if ($data['class'])
		{
			$this->db->where_in('id', explode(',', $data['class']));

			$class = array();

			foreach ($this->db->get('buildings_class')->result_array() as $row)
				$class[] = $row['name'];

			if (count($class))
				$text['class'] = "<strong>Класс жилья:</strong><br />\t".implode("<br />\t", $class);
		}

		// Тип дома
		if ($data['type'])
		{
			$this->db->where_in('id', explode(',', $data['type']));

			$type = array();

			foreach ($this->db->get('type')->result_array() as $row)
				$type[] = $row['name'];

			if (count($type))
				$text['type'] = "<strong>Класс жилья:</strong><br />\t".implode("<br />\t", $type);
		}

		// Комнатность
		if (count($_POST['room']))
			$text['room'] = "<strong>Кол-во комнат:</strong><br />\t".implode("<br />\t", $_POST['room']);

		// Дополнительные параметры
		if (count($_POST['param']))
			$text['param'] = "<strong>Дополнительные параметры:</strong><br />\t".implode("<br />\t", $_POST['param']);

		$result = implode("<br /><br />", $text);

		$userPhone=strip_tags(trim($_POST['phone']));
		$ip = $this->get_ip();
		$minfo = $this->get_ip_info($ip);
		$ok = false;
		//$info = $this->get_ip_info($ip);

		$message = "<b>Заявка на подбор новостроек с сайта m16-estate.ru</b>";
		$message.= "<br />Телефон - ".$userPhone;
		$message.= "<br />IP: ".$ip;
		$message.= "<br />Местоположение: ".$minfo['city'].', '.$minfo['region'].', '.$minfo['district'];
		$message.= "<br><br>Время отправки: ".date('H:i:s d.m.Y')."";
		$message.= "<hr />";
		$message.= $result;

		$this->load->library('phpmailer'); //Класс phpmailer
		$this->phpmailer->ClearAllRecipients();
		// Кодировка
		$this->phpmailer->CharSet	= "utf-8";
		$this->phpmailer->SMTPDebug	= 1;
		$this->phpmailer->CharSet	= 'UTF-8';
		$this->phpmailer->IsSMTP();
		$this->phpmailer->Host		= 'smtp.yandex.ru';
		$this->phpmailer->Port		= 25;
		$this->phpmailer->SMTPSecure= 'tls';
		$this->phpmailer->SMTPAuth	= true;
		$this->phpmailer->Username	= 'm16.noreplay@yandex.ru';
		$this->phpmailer->Password	= 'Vfkfattd016';
		$config = &get_config(); // получаем конфиги
		//Емайл получателя
		$this->phpmailer->AddAddress($config['apex_email'],$config['apex_names']); //Мой адрес
		$this->phpmailer->SetFrom('m16.noreplay@yandex.ru', 'M-16');
		//Тема
		$this->phpmailer->Subject = 'Заявка на подбор новостроек с M16-ESTATE.RU | '.$theme;
		//Тип
		$this->phpmailer->ContentType = 'text/html';
		//Текст
		$this->phpmailer->Body		= $message;
		$this->phpmailer->MsgHTML	= $message;
		$ok = $this->phpmailer->send(); // return true; else return false;

        $data = [
            'phone' => $userPhone,
            'ip' => $ip,
            'message' => $message,
            'oauth_token' => '05fa7144fd39051c2b3e0e512f357239'
        ];
        $url = 'https://m16.kv1.ru/api/orders/post?' . http_build_query($data);
        $req = file_get_contents($url);

		echo json_encode([
		    'ok' => $ok,
            "debug" => $req,
        ]);
	}
}