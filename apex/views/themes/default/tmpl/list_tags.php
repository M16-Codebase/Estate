<?php
include'/var/www/estate/data/www/m16-estate.ru/releases/20151208131140/apex/views/themes/default/pages/service.php';
$data=sqlGetFull('ci_news','tag');
//print_r($data);
$tags=array();
$tagi=array();
foreach($data as $yag){
	if(strlen($yag['tag'])>1){
		$th=explode(',',$yag['tag']);
		foreach($th as $tag){
			if(strlen($tag)>1){
				$tags[]=mb_strtolower(trim($tag));
			}
		}
	}
}
foreach($tags as $tag){
	$pret=explode(' ',$tag);
	if(array_intersect($pret,array_keys($tagi))){
		$tagi[$tag]=(int)$tagi[$tag]+1;
	}else{
		$tagi[$tag]=1;
	}
}
$data=$tagi;
eA($tagi);
eH();
$cmp = function ($a, $b) use ($data) {
  if ($data[$a] == $data[$b]) {
    return $a < $b ? -1 : 1;
  }
  return $data[$a] > $data[$b] ? -1 : 1;
};

uksort($data, $cmp);
echo'<div id="supertags" class="supertags" style="height:78px; overflow:hidden;">';
if(strpos('|'.$_SERVER['REQUEST_URI'],'tag')){
	$ctag=explode('/',urldecode($_SERVER['REQUEST_URI']));
	echo'<div class="tag-placing" style="margin: 0 auto;"><a class="new-tags" href="/news/tag/'.$ctag[3].'">'.$ctag[3].'</a></div>';
}else{
	foreach(array_keys($data) as $the){
		echo'<div class="tag-placing"><a class="new-tags" href="/news/tag/'.$the.'">'.$the.'</a></div>';
	}
}
function sqlGetFull($table,$subject){
	$mysqli = new mysqli('localhost', 'ned', '5M2i1R0q', 'ned');
	if ($mysqli->connect_error) {
		die('Ошибка подключения (' . $mysqli->connect_errno . ') '
				. $mysqli->connect_error);
	}
	
	$mysqli->query("SET NAMES 'utf8mb4'");
	$result=array();
	//echo("SELECT ".$subject." FROM ".$table." WHERE ".$where);
	//echo'<br>';
	if ($data = $mysqli->query("SELECT ".$subject." FROM ".$table)) {
		if (($data->num_rows)==1){
			$row = $data->fetch_array(MYSQLI_ASSOC);
			$result[]=$row;
			return $result;
		}elseif(($data->num_rows)==0){
			return null;
		}else{
			while ($row = $data->fetch_assoc()){
				array_push($result,$row);
			}
			return $result;
		}
	}else{
		$result[]=$mysqli->error;
		return $result;
	}
	$data->close();
	$mysqli->close();
}
?>