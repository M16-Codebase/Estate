<?php
function rus2translit($string) {
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => 'aea',  'ы' => 'y',   'ъ' => 'asa',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

        
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => 'afa',  'Ы' => 'Y',   'Ъ' => 'aca',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    return strtr($string, $converter);
}
function str2url($str) {
    // переводим в транслит
    $str = rus2translit($str);
    // в нижний регистр
    $str = strtolower($str);
    // заменям все ненужное нам на "-"
    $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
    // удаляем начальные и конечные '-'
    $str = trim($str, "-");
    return $str;
}
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$xmlstr = file_get_contents('asset/uploads/crm/SiteDataEstate.xml');
$feed=json_decode(XML2JSON($xmlstr),true);
if(isset($_GET['sstr'])){
	$sears=$_GET['sstr'];
}else{
	$sears='';
}

	$buldir=array();
	$search=array();
	$jk=array();
	foreach($feed['Builders']['Builder'] as $value){
		if(stripos('|'.$value['name'],$sears)!==false){
			$search['Строители'][]=$value;
		}
	}
	foreach($feed['Subways']['Subway'] as $value){
		if(stripos('|'.$value['name'],$sears)!==false){
			$search['Метро'][]=$value;
		}
	}
	foreach($feed['Regions']['Region'] as $value){
		if(stripos('|'.$value['name'],$sears)!==false){
			$search['Районы'][]=$value;
		}
	}
	foreach($feed['Banks']['Bank'] as $value){
		if(stripos('|'.$value['name'],$sears)!==false){
			$search['Банки'][]=$value;
		}
	}
	foreach($feed['BankPrograms']['BankProgram'] as $value){
		if(stripos('|'.$value['name'],$sears)!==false){
			$search['Программы банков'][]=$value;
		}
	}
	foreach($feed['Blocks']['Block'] as $value){
		if(stripos('|'.mb_strtolower($value['title']),mb_strtolower($sears))!==false){
			$search['Объекты'][]=$value;
			foreach($feed['Builders']['Builder'] as $vul){
				if($vul['id']==$value['builderid']){
					$buldir[]=$vul['name'];
				}
			}
		}
		$jk[]=$value['title'];
	}
	//echo(mb_detect_encoding($jk[0]));
	setlocale(LC_ALL, "Russian_Russia.1251");
	sort($jk, SORT_LOCALE_STRING);
?>
<script>
//console.log('pssfsdfsdf');
function tolane(){
	//console.log('pssfsdfsdf');
	var sear=document.getElementById("subla").options[document.getElementById("subla").options.selectedIndex].text;
	document.getElementById("sstr").value=sear;
	//console.log(sear);
}
</script>
<div style="max-width: 1500px; margin: 0 auto;">
<div style="margin: 0 auto;">
<form id="foreq" action="feedParser" method="GET">
	<input id="sstr" name="sstr" type="text" placeholder="Например, LEGEN...">
	ИЛИ
	<select id="subla" OnChange='tolane();'>
		<?php foreach($jk as $value){ ?>
			<option id="seles" name="seles" type="text" value="seles"><?php echo $value?></option>
		<?php } ?>
	</select>
	<button type="submit"> ISKAT </button>
<form>
</div>
<br>
<?php
if(isset($_GET['sstr'])){
	foreach($search['Объекты'] as $key=>$value){?>
		<div><big><strong><?php echo $value['title'];?>:</strong></big>
		
			<div style="padding-left:20px;">
			Описание:
			</div>
			<div style="padding-left:50px;"><a style="cursor:hand" onclick="sh('<?php echo str2url(substr($value['note'],0,10));?>');">Раскрыть</a>
			</div><div style="padding-left:50px; display:none; width:1500px;" id="<?php echo str2url(substr($value['note'],0,10));?>"><?php echo nl2br($value['note']);?></div>
			<hr>
			<div style="padding-left:20px;">
			ID:
			</div>
			<div style="padding-left:50px;"><a onclick="sh('<?php echo str2url(substr($value['id'],0,10));?>');">Раскрыть</a>
			</div><div style="padding-left:50px; display:none; width:1500px;" id="<?php echo str2url(substr($value['id'],0,10));?>"><?php echo $value['id'];?></div>
			<hr>
			<div style="padding-left:20px;">
			Адрес:
			</div>
			<div style="padding-left:50px;"><a onclick="sh('<?php echo str2url(substr($value['address'],0,10));?>');">Раскрыть</a>
			</div><div style="padding-left:50px; display:none; width:1500px;" id="<?php echo str2url(substr($value['address'],0,10));?>"><?php echo $value['address'];?></div>
			<hr>
			<div style="padding-left:20px;">
			Широта:
			</div>
			<div style="padding-left:50px;"><a onclick="sh('<?php echo str2url(substr($value['latitude'],0,10));?>');">Раскрыть</a>
			</div><div style="padding-left:50px; display:none; width:1500px;" id="<?php echo str2url(substr($value['latitude'],0,10));?>"><?php echo $value['latitude'];?></div>
			<hr>
			<div style="padding-left:20px;">
			Долгота:
			</div>
			<div style="padding-left:50px;"><a onclick="sh('<?php echo str2url(substr($value['longitude'],0,10));?>');">Раскрыть</a>
			</div><div style="padding-left:50px; display:none; width:1500px;" id="<?php echo str2url(substr($value['longitude'],0,10));?>"><?php echo $value['longitude'];?></div>
			<hr>
			<div style="padding-left:20px;">
			Застройщик:
			</div>
			<div style="padding-left:50px;"><a onclick="sh('<?php echo str2url(substr($value['builderid'],0,10));?>');">Раскрыть</a>
			</div><div style="padding-left:50px; display:none; width:1500px;" id="<?php echo str2url(substr($value['builderid'],0,10));?>"><?php echo $buldir[$key];?></div>
			<hr>
			<div style="padding-left:20px;">
			Аватар:
			</div>
			<div style="padding-left:50px;"><a onclick="sh('<?php echo str2url(substr($value['avatar'],0,10));?>');">Раскрыть</a>
			</div><div style="padding-left:50px; display:none; width:1500px;" id="<?php echo str2url(substr($value['avatar'],0,10));?>"><img height="560" width="960" src="<?php echo ('https://m16-estate.ru/asset/uploads/crm/'.$value['avatar']);?>"></div>
			
			
		</div>
		<br>
		<br>
<?php }}else{
	echo'<pre>';print_r(array_keys($feed));echo'</pre>';
}

function XML2JSON($xml) {

        function normalizeSimpleXML($obj, &$result) {
            $data = $obj;
            if (is_object($data)) {
                $data = get_object_vars($data);
            }
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $res = null;
                    normalizeSimpleXML($value, $res);
                    if (($key == '@attributes') && ($key)) {
                        $result = $res;
                    } else {
                        $result[$key] = $res;
                    }
                }
            } else {
                $result = $data;
            }
        }
        normalizeSimpleXML(simplexml_load_string($xml), $result);
        return json_encode($result);
    }
?>
</div>
<script type="text/javascript">// <![CDATA[
function sh(id) {
console.log(document.getElementById(id).style);
obj = document.getElementById(id);
if( obj.style.display == "none" ) { obj.style.display = "block"; } else { obj.style.display = "none"; }
}
// ]]></script>