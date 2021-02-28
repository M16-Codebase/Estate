<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
require_once 'fb.php';
$html='';
$fhtml='';
$arrs=array();

function eA($array){
	global $arrs;
	//FB::log($array);
	$arrs[]=$array;
}
function eT($string){
	global $html;
	//FB::log($string);
	$html=$html.$string.'<br>';
}
function eH(){
	//echo buildHtml();
}
function buildHtml(){
	global $html,$fhtml,$arrs;
	$ip=GetIP();
	if(strpos('|'.$ip,'78.37.59.179')){
		echo '&nbsp; [INFO]: IP: '.$ip.'<br>';
		echo '&nbsp; [INFO]: Services ready<br>';
		echo'&nbsp; [INFO]: Debugger ready<br>';
		$fhtml=
		'
		<div class="phplog">
		'.$html;
		foreach($arrs as $ar){
			echo'<pre>';print_r($ar);echo'</pre>';
		}
		$fhtml=$fhtml.'</div>';
	}else{
		$fhtml=
		'
		<div class="phplog" style="display:none">
		'.$html.'
		</div>
		';
	}
	return $fhtml;
}

function GetIP() {
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
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
?>