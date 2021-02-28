<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('allow_url_fopen', 1);
include_once('shd.php');
$descriptor = fopen('list.txt', 'r');
if ($descriptor) {
	$cn=0;
    while (($string = fgets($descriptor)) !== false) {
        $urls[]=substr($string, 0, -2);
		$cn=$cn+1;
    }
    fclose($descriptor);

} else {
    echo 'Невозможно открыть указанный файл';
}

foreach($urls as $key=>$value){
	$stre=str_get_html((string)file_get_contents($value))->find('div[class=about_page_company_row_value]',-1)->innertext;
	if(!strpos($stre,'@') && strlen($stre)>3){
		$datas[]=str_get_html((string)file_get_contents($value))->find('div[class=about_page_company_row_value]',-1)->innertext;
	}
}
echo'<pre>';print_r($datas);echo'</pre>';
?>