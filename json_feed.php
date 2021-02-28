<?php
header("Access-Control-Allow-Origin: *");
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '2048M');

if ( isset( $_POST['1st'] )) {
    $xml = file_get_contents('/var/www/estate/data/www/m16-estate.ru/shared/asset/uploads/crm/SiteDataEstate.xml');
    $feed=json_decode(json_encode(simplexml_load_string($xml)),true);
    $data=[];
    foreach($feed['Blocks']['Block'] as $value){
        $datum['id']=$value['@attributes']['id'];
        $datum['title']=$value['@attributes']['title'];
        $data[]=$datum;
    }
    $data=json_encode($data);
    echo $data;
}elseif ( isset( $_POST['zhk_id'] ) ) {
    $xml = file_get_contents('/var/www/estate/data/www/m16-estate.ru/shared/asset/uploads/crm/SiteDataEstate.xml');
    $feed=json_decode(json_encode(simplexml_load_string($xml)),true);
    $data=[];
    foreach($feed['Apartments']['Apartment'] as $value){
        if($value['@attributes']['blockid']==$_POST['zhk_id']){

            $datum['sq1']=$value['@attributes']['stotal'];
            if(strpos($value['@attributes']['sroom'],'+')){
                $lsq=0;
                eval('$lsq = '.$value['@attributes']['sroom'].";");
                $datum['sq2']=$lsq;
            }else{
                $datum['sq2']=$value['@attributes']['sroom'];
            }
            $datum['sq3']=$value['@attributes']['skitchen'];
            if(strlen($value['@attributes']['rooms'])>1){
                $datum['euro']=1;
                $datum['rooms']=substr($value['@attributes']['rooms'],-1,1);
            }else{
                $datum['euro']=0;
                $datum['rooms']=$value['@attributes']['rooms'];
            }
            if(isset($value['@attributes']['flatplan'])) {
                $datum['plan'] = $value['@attributes']['flatplan'];
            }else{
                $datum['plan']='';
            }
            $data[]=$datum;
        }
    }
    $data=json_encode($data);
    echo $data;
}else {
    print_r($_POST);
}

