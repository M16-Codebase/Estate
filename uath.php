<?php
$data[]=$_POST['id'];
$data[]=(int)$_POST['aid']+1;
//print_r($data);
echo sqlUpdate('ci_news',"`author`=$data[1]","`id`=$data[0]");
return 0;
function sqlUpdate($table,$set,$where){
    $mysqli = new mysqli('localhost', 'ned', '5M2i1R0q', 'ned');
    if ($mysqli->connect_error) {
        die('Ошибка подключения (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
    }

    $mysqli->query("SET NAMES 'utf8mb4'");
    echo("UPDATE ".$table." SET ".$set." WHERE ".$where);
    if ($data=$mysqli->query("UPDATE ".$table." SET ".$set." WHERE ".$where)) {
        return $data;
    }else{
        return $data;
    }
    $data->close();
    $mysqli->close();
}
?>