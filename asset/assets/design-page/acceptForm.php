<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
if(strpos('|'.$_POST["target"],'Заказ кейса')){
	$result = array(
		'theme' => $_POST["target"],
		'name' => $_POST["authorp"],
		'jk' => $_POST["jkp"],
		'kv' => $_POST["kvp"],
		'mail' => $_POST["emailp"]
	); 
	$data=
	'
	Имя: '.$result['name'].'
	Email: '.$result['mail'].'
	Квартир: '.$result['kv'].'
	Жк: '.$result['jk'].'
	';
	if(mail('samsonov@m16.bz', $result['theme'], $data)){
		print_r($result);
	}else{
		echo 'pesos';
	}
}else{
	$result = array(
		'theme' => $_POST["target"],
		'name' => $_POST["authorp"],
		'mail' => $_POST["emailp"]
	); 
	$data=
	'
	Имя: '.$result['name'].'
	Email: '.$result['mail'].'
	';
	if(mail('samsonov@m16.bz', $result['theme'], $data)){
		print_r($result);
	}else{
		echo 'pesos';
	}
}
?>