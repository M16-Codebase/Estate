<?php 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if(isset($_POST['url'])){
	$link=explode('/',$_POST['url']);
	$raty=sqlGet('ci_buildings','`rating`,`raters`',"`link`='$link[4]'");
	//print_r($raty);
	$rating= floor(($raty['rating']/$raty['raters']))*20;
	echo $rating;
}else{
	$link=explode('/',$_POST['uri']);
	$rat=(int)$_POST['rating'];
	if(sqlUpdate('ci_buildings',"`rating`=`rating`+$rat,`raters`=`raters`+1","`link`='$link[4]'")){
		echo('Вы поставили отметку: '); echo($_POST['rating']);
	}
}




function sqlGet($table,$subject,$where){
		$mysqli = new mysqli('localhost', 'ned', '5M2i1R0q', 'ned');
		if ($mysqli->connect_error) {
			die('Ошибка подключения (' . $mysqli->connect_errno . ') '
					. $mysqli->connect_error);
		}
		
		$mysqli->query("SET NAMES 'utf8mb4'");
		//echo("SELECT ".$subject." FROM ".$table." WHERE ".$where);
		if ($data = $mysqli->query("SELECT ".$subject." FROM ".$table." WHERE ".$where)) {
			if (($data->num_rows)==1){
				$row = $data->fetch_array(MYSQLI_ASSOC);
				return $row;
			}elseif(($data->num_rows)==0){
				echo 'null';
				exit;
			}else{
				$result=array();
				while ($row = $data->fetch_assoc()){
					array_push($result,$row);
				}
				return $result;
			}
		}else{
			echo $mysqli->error;
			exit;
		}
		$data->close();
		$mysqli->close();
	}
	
	function sqlUpdate($table,$set,$where){
		$mysqli = new mysqli('localhost', 'ned', '5M2i1R0q', 'ned');
		if ($mysqli->connect_error) {
			die('Ошибка подключения (' . $mysqli->connect_errno . ') '
					. $mysqli->connect_error);
		}
		
		$mysqli->query("SET NAMES 'utf8mb4'");
		
		if ($data=$mysqli->query("UPDATE ".$table." SET ".$set." WHERE ".$where)) {
			return $data;
		}else{
			return null;
		}
		$data->close();
		$mysqli->close();
	}

?>