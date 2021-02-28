<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ignore_user_abort(true);
set_time_limit(0);
echo 22;
if(isset($_POST['naemm'])){
    sendAskData();
}else {
    if (isset($_POST['name'])) {
        object2file($_POST, '/var/www/estate/data/www/m16-estate.ru/releases/20151208131140/apex/views/themes/default/pages/post.txt');
        sendData();
    } else {
        echo '<pre>';
        print_r(object_from_file('/var/www/estate/data/www/m16-estate.ru/releases/20151208131140/apex/views/themes/default/pages/post.txt'));
        echo '</pre>';
    }
}
function object2file($value, $filename)
{
	$str_value = serialize($value);
	
	$f = fopen($filename, 'a');
	fwrite($f, 
	$str_value);
	fclose($f);
}
function object_from_file($filename)
{
	$file = file_get_contents($filename);
	$value = unserialize($file);
	return $value;
}
function sendData(){
    $data='
	Цель: '.$_POST['goal'].'
	Типы квартир: '.implode(',',$_POST['form']['54371']).'
	Тип ремонта: '.implode(',',$_POST['form']['94289']).'
	Часть города '.implode(',',$_POST['form']['68984']).'
	Примерное время приобретения: '.implode(',', isset($_POST['form']['62698']) ? $_POST['form']['62698'] : []).'
	Агентство недвижимости: '.implode(',',$_POST['form']['27018']).'
	Имя: '.$_POST['form']['64067'].'
	Номер телефона: '.$_POST['form']['66136'].'
	';
	mail('shkaphik00@gmail.com', 'Новый запрос с теста', $data);
	$url = 'https://m16.kv1.ru/api/orders/post?oauth_token=05fa7144fd39051c2b3e0e512f357239';
	$d = [
		'message' => $data,
		'name' => $_POST['form']['64067'],
		'phone' => $_POST['form']['66136'],
	];
	$options = [
		'http' => [
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($d)
		]
	];
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
}
function sendAskData(){
    $data=
        '
	Имя: '.$_POST['naemm'].'
	Номер телефона: '.$_POST['phone'].'
	';
    mail('shkaphik00@gmail.com', 'Новый запрос с новостей', $data);
}
?>