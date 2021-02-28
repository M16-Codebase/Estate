<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$xmlstr = simplexml_load_file('https://m16-estate.ru/asset/uploads/crm/SiteDataEstate.xml');
echo '<pre>';print_r($xmlstr); echo '</pre>';
?>