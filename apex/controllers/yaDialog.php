<?php
	class yaDialog extends MX_Controller {
		public $flags=array();
		public function index($part=null)
    {
		echo 'pesos'. PHP_EOL;
		//$data=array();
		//$data=defineData();
		
		//req_log_pusk($data);
		
	}
	public function defineData(){
		$requestSession=array();
		$requestSession=defineSession();
		
		$requestClient=array();
		$requestClient=defineClient();
		
		$requestRequest=array();
		$requestRequest=defineRequest();
		
		$requestMeta=array();
		$requestMeta=defineMeta();
		
		$requestFlags=array();
		$requestFlags=defineFlags();
		
		$dataRow=array();
		$dataRow['session']=$requestSession;
		$dataRow['client']=$requestClient;
		$dataRow['content']=$requestRequest;
		$dataRow['meta']=$requestMeta;
		defineFlags();
		$dataRow['flags']=$flags;
		return $dataRow;
		
	}
	
	public function defineSession(){
		$data=$_POST['session'];
		$result=array();
		$result['meId']=$data['message_id'];
		$result['seId']=$data['session_id'];
		$result['skId']=$data['skill_id'];
		$result['usId']=$data['user_id'];
		//req_log_pusk($result);
		return $result;
	}
	
	public function defineClient(){
		$data=$_POST['session'];
		$id=$data['user_id'];
		$data=$_POST['meta'];
		$device=$data['client_id'];
		$device=explode('(',$device);
		$device=explode(')',$device[1]);
		$device=$device[0];
		$result=array();
		$local_id=getLocal($id,$device);
		$result['local']=$local_id;
		return $result;
	}
	
	public function getLocal($id,$device){
		if($data=sqlGet("clients","`id`","`id`='$id',`req_from`=$device")){
			return $data['id'];
		}else{
			if(sqlInsert('client','`id`,`req_from`',"'$id','$device'")){
				return $id;
			}else{
				return null;
			}
		}
		
	}
	
	public function defineRequest(){
		$data=$_POST['request'];
		$result=array();
		switch($data['command']){
			case 'smcmd1':
				$result['local_id']=0;
				break;
			case 'smcmd2':
				$result['local_id']=1;
				break;
		}
		$result['req_command']=$data['command'];
		$result['original']=$data['original_utterance'];
		if(isset($data['payload'])){
			$result['payload']=$data['payload'];
		}
		return $result;
	}
	
	public function defineMeta(){
		$data=$_POST['meta'];
		$result=array();
		$result['locale']=$data['locale'];
		$result['timezone']=$data['timezone'];
		$result['client']=$data['client_id'];
		//req_log_pusk($result);
		return $result;
	}
	
	public function defineFlags(){
		$data=$_POST['meta'];
		if($data['locale']=='ru-RU'){
			addFlag('ruloc',true);
		}else{
			addFlag('ruloc',false);
		}
		$data=$_POST['request'];
		if(isset($data['markup']){
			addFlag('dang_cont',true);
		}else{
			addFlag('dang_cont',false);
		}
		if($data['type']=='SimpleUtterance'){
			addFlag('enter',true);
		}else{
			addFlag('enter',false);
		}
		$data=$_POST['session'];
		if($data['new']==true){
			addFlag('new',true);
		}else{
			addFlag('new',false);
		}
	}
	
	public function addFlag($key,$value){
		$flags[$key]=$value;
	}
	
	public function req_log_pusk($data){
		$list='`mesage_id`,`session_id`,`skill_id`,`user_id`,`locale`,`timezone`,`client_id`,`local_id`';
		$values=
			"'".$data['session']['meId']."','".$data['session']['seId']."','"
			.$data['session']['skId']."','".$data['session']['usId']."','"
			.$data['meta']['locale']."','".$data['meta']['timezone']."','"
			.$data['meta']['client']."','".$data['client']['local']."'";
		echo('<p>createLogString: '.sqlInsert('log',$list,$values).';</p>');
			
	}
	
	public function sqlGet($table,$subject,$where){
		$mysqli = new mysqli('138.201.58.198', 'ya_dialog', 'odmen', 'dialog_estate');
		if ($mysqli->connect_error) {
			die('Ошибка подключения (' . $mysqli->connect_errno . ') '
					. $mysqli->connect_error);
		}
		
		$mysqli->query("SET NAMES 'utf8mb4'");
		
		if ($data = $mysqli->query("SELECT ".$subject." FROM ".$table." WHERE ".$where)) {
			if (($data->num_rows)=1){
				$row = $data->fetch_array(MYSQLI_ASSOC);
				return $row;
			}elseif(($data->num_rows)=0){
				return null;
			}else{
				$result=array();
				while ($row = $data->fetch_assoc()){
					array_push($result,$row);
				}
				return $result;
			}
		}else{
			return null;
		}
		$data->close();
		$mysqli->close();
	}
	
	public function sqlUpdate($table,$set,$where){
		$mysqli = new mysqli('138.201.58.198', 'ya_dialog', 'odmen', 'dialog_estate');
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
	
	public function sqlInsert($table,$list,$values){
		$mysqli = new mysqli('138.201.58.198', 'ya_dialog', 'odmen', 'dialog_estate');
		if ($mysqli->connect_error) {
			die('Ошибка подключения (' . $mysqli->connect_errno . ') '
					. $mysqli->connect_error);
		}
		
		$mysqli->query("SET NAMES 'utf8mb4'");
		
		if ($data=$mysqli->query("INSERT INTO ".$table." (".$list.") VALUES (".$values.")")) {
			return $data;
		}else{
			return null;
		}
		$data->close();
		$mysqli->close();
	}
}
?>
