<?php
/* ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); */
$pages=prepareLabels();
$items='';
foreach($pages as $page){
    $items.=prepareItem($page['link']);
}
header('Content-Type: application/rss+xml; charset=UTF-8');
$full='';
$full.='<?xml version="1.0" encoding="UTF-8"?><rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/"  xmlns:turbo="http://turbo.yandex.ru" version="2.0"><channel>' . PHP_EOL;
$full.= '<title>M16-Недвижимость</title>' . PHP_EOL;
$full.= "<link>https://m16-estate.ru</link>" . PHP_EOL;
$full.= '<description>
Продажа недвижимости в Санкт-Петербурге и Ленинградской области! ✔ Лучшие предложения на рынке жилой, загородной и коммерческой недвижимости на официальном сайте агентства Вячеслава Малафеева «М16-Недвижимость»!
</description>' . PHP_EOL;
$full.= '<language>ru</language>' . PHP_EOL;
$full.= $items. PHP_EOL;
$full.= '</channel>' . PHP_EOL;
$full.= '</rss>' . PHP_EOL;
//$full = preg_replace('/(?:^|\G)  /um', "\t", $full);
//$full = preg_replace('/[\x00-\x1F\x7F]/u', '', $full);
echo $full;
exit;
function prepareItem($label){
    $data=
        '<item turbo="true">
	'.prepareTitle($label).'
	'.prepareLink($label).'
	'.prepareContent($label).'
</item>';
    return $data;
}
function prepareTitle($label){
    $rows=array();
    $rows=sqlGet('ci_buildings','`title`',"`link`='".$label."'");
    $ttl=str_replace('&','&amp;',$rows[0]['title']);
    $data='<title>'.$ttl.'</title>';
    return $data;
}
function prepareLabels(){
    $data=sqlGetFull('ci_buildings','`link`',"`razdelu`=0 AND `banned`=0");
    return $data;
}
function prepareLink($label){
    $data='<link>https://m16-estate.ru/buildings/'.$label.'</link>';
    return $data;
}

function prepareContent($label){
    $dataRow=array();
    $dataRow['header']=prepHeader($label);
    $dataRow['slider']=prepSlider($label);
    $dataRow['rating']=prepRating($label);
    $dataRow['video']=prepVideo($label);
    $dataRow['params']=prepParams($label);
    $dataRow['appartments']=prepAppart($label);
    $dataRow['same']=prepSame($label);
    $dataRow['callback']=prepCall($label);
    $data=
        '
	<turbo:content>
		<![CDATA[
			'.$dataRow['header'].$dataRow['slider'].$dataRow['rating'].$dataRow['video'].$dataRow['params'].$dataRow['appartments'].$dataRow['same'].$dataRow['callback'].'
		]]>
    </turbo:content>
	';
    return $data;
}
function prepHeader($label){
    $rows=array();
    $dataRows=array();
    $rows=sqlGet('ci_buildings','`name`',"`link`='".$label."'");
    foreach($rows as $value){
        $dataRows['h1']='<h1>'.$value['name'].'</h1>';
    }
    $rows=sqlGet('ci_buildings','`price`',"`link`='".$label."'");
    foreach($rows as $value){
        $dataRows['h2']='<h2>от '.number_format(((int)$value['price']/1000000), 2, '.', ' ').' млн руб</h2>';
    }
    $rows=sqlGet('ci_buildings','`bigfoto`',"`link`='".$label."'");
    foreach($rows as $value){
        $dataRows['img']='<!--<figure>
      <img src="https://m16-estate.ru'.$value['bigfoto'].'">
      </figure> -->';
    }
    $data='<header>'.$dataRows['h1'].$dataRows['h2'].$dataRows['img'].'</header>';
    return $data;
}
function prepVideo($label){
    $videolink=sqlGet('ci_buildings','`video_code`',"`link`='".$label."'");
    if(strlen($videolink[0]['video_code'])>10){
        $videolink=str_replace('https://www.youtube.com/watch?v=','',$videolink[0]['video_code']);
        $data='
		    <iframe
                width="640"
                height="360"
                src=
                 "https://www.youtube.com/embed/'.$videolink.'" 
                frameborder="0" 
                allowfullscreen>
           </iframe>
		';
    }else{
        $data='';
    }
    return $data;
}
function prepSlider($label){
    $srd=sqlGet('ci_buildings','`foto`',"`link`='".$label."'");
    $sr=explode('/asset/uploads/images/buildings/',$srd[0]['foto']);
    if(count($sr)>2){
        array_shift($sr);
        $sr=array_slice($sr,0,3);
        foreach($sr as $value){
            $res[]=substr($value,0,strpos($value,'"'));
        }
        $data='
		<div data-block="slider">
			<figure><img src="https://m16-estate.ru/asset/uploads/images/buildings/resizebig/'.$res[0].'" /></figure>
			<figure><img src="https://m16-estate.ru/asset/uploads/images/buildings/resizebig/'.$res[1].'" /></figure>
			<figure><img src="https://m16-estate.ru/asset/uploads/images/buildings/resizebig/'.$res[2].'" /></figure>
		</div>';
        return $data;
    }else{
        $sr=explode('/asset/uploads/crm/',$srd[0]['foto']);
        if(count($sr)>2){
            array_shift($sr);
            $sr=array_slice($sr,0,3);
            foreach($sr as $value){
                $res[]=substr($value,0,strpos($value,'"'));
            }
            $data='
			<div data-block="slider">
				<figure><img src="https://m16-estate.ru/asset/uploads/crm/'.$res[0].'" /></figure>
				<figure><img src="https://m16-estate.ru/asset/uploads/crm/'.$res[1].'" /></figure>
				<figure><img src="https://m16-estate.ru/asset/uploads/crm/'.$res[2].'" /></figure>
			</div>';
            return $data;
        }else{
            return'';
        }
    }
}
function prepRating(){
    $sr=sqlGet('ci_buildings','`rating`,`raters`',"`razdelu`=0");
    $data=intval(($sr[0]['rating'])/($sr[0]['raters']));
    $data='
	<div itemscope itemtype="http://schema.org/Rating">
		<meta itemprop="ratingValue" content="'.$data.'">
		<meta itemprop="bestRating" content="5">
	</div>';
    return $data;
}
function prepParams($label){
    $dataRows=sqlGet('ci_buildings','`area`,`adress`,`metro_id`,`rayon_id`,`korpus_value`,`builder_id`,`xml_text`,`text`,`otdelka`,`infrastruct`',"`link`='".$label."'");
    $datas='';
    if(strlen($dataRows[0]['xml_text'])>20){
        $datas.=
            '
		<div data-block="item" data-title="Описание">
			'.
            $dataRows[0]['xml_text']
            .'
		</div>
		';
    }else{
        $datas.=
            '
		<div data-block="item" data-title="Описание">
			'.
            $dataRows[0]['text']
            .'
		</div>
		';
    }
    if(strlen($dataRows[0]['otdelka'])>20){
        $datas.=
            '
		<div data-block="item" data-title="Отделка квартир">
			'.
            $dataRows[0]['otdelka']
            .'
		</div>
		';
    }
    if(strlen($dataRows[0]['infrastruct'])>20){
        $datas.=
            '
		<div data-block="item" data-title="Инфраструктура">
			'.
            $dataRows[0]['infrastruct']
            .'
		</div>
		';
    }
    $data=
        '
		<table data-invisible="true">
		   <tr>
			  <!--Заголовок таблицы-->
			  <th><img src="https://m16-estate.ru/asset/assets/img/srocico.png"></th>
			  <th>Срок сдачи: '.dataKv($dataRows[0]['korpus_value']).'</th>
		   </tr>
		   <tr>
			  <!--Строка таблицы-->
			  <td><img src="https://m16-estate.ru/asset/assets/img/placeico.png"></td>
			  <td>'.$dataRows[0]['area'].', '.$dataRows[0]['adress'].'</td>
		   </tr>
		   <tr>
			  <!--Строка таблицы-->
			  <td><img src="https://m16-estate.ru/asset/assets/img/metroico.png"></td>
			  <td>'.metro($dataRows[0]['metro_id']).'</td>
		   </tr>
		   <tr>
			  <!--Строка таблицы-->
			  <td>Район:</td>
			  <td>'.rayon($dataRows[0]['rayon_id']).'</td>
		   </tr>
		   <tr>
			  <!--Строка таблицы-->
			  <td>Застройщик:</td>
			  <td>'.builder($dataRows[0]['builder_id']).'</td>
		   </tr>
		</table>
		<div data-block="accordion">
			'.
        $datas
        .'
		</div>	
		';
    return $data;
}

function prepAppart($label){
    $id=sqlGet('ci_buildings','`id`',"`link`='".$label."'");
    $id=$id[0]['id'];
    $dataRow[]=sqlGet('ci_apartments','`price`,`square_all`,`main_foto`,`room_id`,`id`',"`novostroy_id`='".$id."'");
    //echo'<pre>';print_r($dataRow);echo'</pre>';
    //exit;
    if(count($dataRow[0])>0){
        $rooms=array();
        $rooms['std'][0]=0;
        $rooms['1k'][0]=0;
        $rooms['2k'][0]=0;
        $rooms['3k'][0]=0;
        $rooms['4k'][0]=0;
        $rooms['5k'][0]=0;
        $rooms['6k'][0]=0;
        $rooms['7k'][0]=0;
        $rooms['2kk'][0]=0;
        $rooms['3kk'][0]=0;
        $rooms['4kk'][0]=0;
        foreach($dataRow[0] as $key=>$value){
            if($value['room_id']==1){
                $rooms['std'][0]=$rooms['std'][0]+1;
                $rooms['std'][1][]=$key;
            }
            if($value['room_id']==2){
                $rooms['1k'][0]=$rooms['1k'][0]+1;
                $rooms['1k'][1][]=$key;
            }
            if($value['room_id']==3){
                $rooms['2k'][0]=$rooms['2k'][0]+1;
                $rooms['2k'][1][]=$key;
            }
            if($value['room_id']==4){
                $rooms['3k'][0]=$rooms['3k'][0]+1;
                $rooms['3k'][1][]=$key;
            }
            if($value['room_id']==5){
                $rooms['4k'][0]=$rooms['4k'][0]+1;
                $rooms['4k'][1][]=$key;
            }
            if($value['room_id']==6){
                $rooms['5k'][0]=$rooms['5k'][0]+1;
                $rooms['5k'][1][]=$key;
            }
            if($value['room_id']==7){
                $rooms['6k'][0]=$rooms['6k'][0]+1;
                $rooms['6k'][1][]=$key;
            }
            if($value['room_id']==16){
                $rooms['2kk'][0]=$rooms['2kk'][0]+1;
                $rooms['2kk'][1][]=$key;
            }
            if($value['room_id']==17){
                $rooms['3kk'][0]=$rooms['3kk'][0]+1;
                $rooms['3kk'][1][]=$key;
            }
            if($value['room_id']==18){
                $rooms['4kk'][0]=$rooms['4kk'][0]+1;
                $rooms['4kk'][1][]=$key;
            }
        }
        $data='';

        if($rooms['std'][0]>0){
            $data.=
                '
			<div data-block="item" data-title="Студии" data-expanded="false">
				<table data-invisible="true">
					<tr><!--Заголовок таблицы-->
						<th>Площадь</th>
						<th>Стоимость квартиры</th>
						<th>Планировка</th>
					</tr>
			';
            foreach($rooms['std'][1] as $key=>$value){
                if($key>3){
                    break;
                }
                $data.=
                    '
				<tr><!--Строка таблицы-->
					<td>'.$dataRow[0][$value]['square_all'].'</td>
					<td>от '.substr((string)number_format($dataRow[0][$value]['price'], 2, ' ', ' '),0,-3).' руб.</td>
					<td>
						<img class="imgwidth" src="'.checkImg($dataRow[0][$value]['main_foto']).'">
					</td>
				</tr>
				';
            }
            $data.=
                '
				</table>
			</div>
			';
        }
        if($rooms['1k'][0]>0){
            $data.=
                '
			<div data-block="item" data-title="1-комнатные" data-expanded="false">
				<table data-invisible="true">
					<tr><!--Заголовок таблицы-->
						<th>Площадь</th>
						<th>Стоимость квартиры</th>
						<th>Планировка</th>
					</tr>
			';
            foreach($rooms['1k'][1] as $key=>$value){
                if($key>3){
                    break;
                }
                $data.=
                    '
				<tr><!--Строка таблицы-->
					<td>'.$dataRow[0][$value]['square_all'].'</td>
					<td>от '.substr((string)number_format($dataRow[0][$value]['price'], 2, ' ', ' '),0,-3).' руб.</td>
					<td>
						<img class="imgwidth" src="'.checkImg($dataRow[0][$value]['main_foto']).'">
					</td>
				</tr>
				';
            }
            $data.=
                '
				</table>
			</div>
			';
        }
        if($rooms['2k'][0]>0){
            $data.=
                '
			<div data-block="item" data-title="2-комнатные" data-expanded="false">
				<table data-invisible="true">
					<tr><!--Заголовок таблицы-->
						<th>Площадь</th>
						<th>Стоимость квартиры</th>
						<th>Планировка</th>
					</tr>
			';
            foreach($rooms['2k'][1] as $key=>$value){
                if($key>3){
                    break;
                }
                $data.=
                    '
				<tr><!--Строка таблицы-->
					<td>'.$dataRow[0][$value]['square_all'].'</td>
					<td>от '.substr((string)number_format($dataRow[0][$value]['price'], 2, ' ', ' '),0,-3).' руб.</td>
					<td>
						<img class="imgwidth" src="'.checkImg($dataRow[0][$value]['main_foto']).'">
					</td>
				</tr>
				';
            }
            $data.=
                '
				</table>
			</div>
			';
        }
        if($rooms['3k'][0]>0){
            $data.=
                '
			<div data-block="item" data-title="3-комнатные" data-expanded="false">
				<table data-invisible="true">
					<tr><!--Заголовок таблицы-->
						<th>Площадь</th>
						<th>Стоимость квартиры</th>
						<th>Планировка</th>
					</tr>
			';
            foreach($rooms['3k'][1] as $key=>$value){
                if($key>3){
                    break;
                }
                $data.=
                    '
				<tr><!--Строка таблицы-->
					<td>'.$dataRow[0][$value]['square_all'].'</td>
					<td>от '.substr((string)number_format($dataRow[0][$value]['price'], 2, ' ', ' '),0,-3).' руб.</td>
					<td>
						<img class="imgwidth" src="'.checkImg($dataRow[0][$value]['main_foto']).'">
					</td>
				</tr>
				';
            }
            $data.=
                '
				</table>
			</div>
			';
        }
        if($rooms['4k'][0]>0){
            $data.=
                '
			<div data-block="item" data-title="4-комнатные" data-expanded="false">
				<table data-invisible="true">
					<tr><!--Заголовок таблицы-->
						<th>Площадь</th>
						<th>Стоимость квартиры</th>
						<th>Планировка</th>
					</tr>
			';
            foreach($rooms['4k'][1] as $key=>$value){
                if($key>3){
                    break;
                }
                $data.=
                    '
				<tr><!--Строка таблицы-->
					<td>'.$dataRow[0][$value]['square_all'].'</td>
					<td>от '.substr((string)number_format($dataRow[0][$value]['price'], 2, ' ', ' '),0,-3).' руб.</td>
					<td>
						<img class="imgwidth" src="'.checkImg($dataRow[0][$value]['main_foto']).'">
					</td>
				</tr>
				';
            }
            $data.=
                '
				</table>
			</div>
			';
        }
        if($rooms['5k'][0]>0){
            $data.=
                '
			<div data-block="item" data-title="5-комнатные" data-expanded="false">
				<table data-invisible="true">
					<tr><!--Заголовок таблицы-->
						<th>Площадь</th>
						<th>Стоимость квартиры</th>
						<th>Планировка</th>
					</tr>
			';
            foreach($rooms['5k'][1] as $key=>$value){
                if($key>3){
                    break;
                }
                $data.=
                    '
				<tr><!--Строка таблицы-->
					<td>'.$dataRow[0][$value]['square_all'].'</td>
					<td>от '.substr((string)number_format($dataRow[0][$value]['price'], 2, ' ', ' '),0,-3).' руб.</td>
					<td>
						<img class="imgwidth" src="'.checkImg($dataRow[0][$value]['main_foto']).'">
					</td>
				</tr>
				';
            }
            $data.=
                '
				</table>
			</div>
			';
        }
        if($rooms['6k'][0]>0){
            $data.=
                '
			<div data-block="item" data-title="6-комнатные" data-expanded="false">
				<table data-invisible="true">
					<tr><!--Заголовок таблицы-->
						<th>Площадь</th>
						<th>Стоимость квартиры</th>
						<th>Планировка</th>
					</tr>
			';
            foreach($rooms['6k'][1] as $key=>$value){
                if($key>3){
                    break;
                }
                $data.=
                    '
				<tr><!--Строка таблицы-->
					<td>'.$dataRow[0][$value]['square_all'].'</td>
					<td>от '.substr((string)number_format($dataRow[0][$value]['price'], 2, ' ', ' '),0,-3).' руб.</td>
					<td>
						<img class="imgwidth" src="'.checkImg($dataRow[0][$value]['main_foto']).'">
					</td>
				</tr>
				';
            }
            $data.=
                '
				</table>
			</div>
			';
        }
        if($rooms['2kk'][0]>0){
            $data.=
                '
			<div data-block="item" data-title="2ккв (евро)" data-expanded="false">
				<table data-invisible="true">
					<tr><!--Заголовок таблицы-->
						<th>Площадь</th>
						<th>Стоимость квартиры</th>
						<th>Планировка</th>
					</tr>
			';
            foreach($rooms['2kk'][1] as $key=>$value){
                if($key>3){
                    break;
                }
                $data.=
                    '
				<tr><!--Строка таблицы-->
					<td>'.$dataRow[0][$value]['square_all'].'</td>
					<td>от '.substr((string)number_format($dataRow[0][$value]['price'], 2, ' ', ' '),0,-3).' руб.</td>
					<td>
						<img class="imgwidth" src="'.checkImg($dataRow[0][$value]['main_foto']).'">
					</td>
				</tr>
				';
            }
            $data.=
                '
				</table>
			</div>
			';
        }
        if($rooms['3kk'][0]>0){
            $data.=
                '
			<div data-block="item" data-title="3ккв (евро)" data-expanded="false">
				<table data-invisible="true">
					<tr><!--Заголовок таблицы-->
						<th>Площадь</th>
						<th>Стоимость квартиры</th>
						<th>Планировка</th>
					</tr>
			';
            foreach($rooms['3kk'][1] as $key=>$value){
                if($key>3){
                    break;
                }
                $data.=
                    '
				<tr><!--Строка таблицы-->
					<td>'.$dataRow[0][$value]['square_all'].'</td>
					<td>от '.substr((string)number_format($dataRow[0][$value]['price'], 2, ' ', ' '),0,-3).' руб.</td>
					<td>
						<img class="imgwidth" src="'.checkImg($dataRow[0][$value]['main_foto']).'">
					</td>
				</tr>
				';
            }
            $data.=
                '
				</table>
			</div>
			';
        }
        if($rooms['4kk'][0]>0){
            $data.=
                '
			<div data-block="item" data-title="4ккв (евро)" data-expanded="false">
				<table data-invisible="true">
					<tr><!--Заголовок таблицы-->
						<th>Площадь</th>
						<th>Стоимость квартиры</th>
						<th>Планировка</th>
					</tr>
			';
            foreach($rooms['4kk'][1] as $key=>$value){
                if($key>3){
                    break;
                }
                $data.=
                    '
				<tr><!--Строка таблицы-->
					<td>'.$dataRow[0][$value]['square_all'].'</td>
					<td>от '.substr((string)number_format($dataRow[0][$value]['price'], 2, ' ', ' '),0,-3).' руб.</td>
					<td>
						<img class="imgwidth" src="'.checkImg($dataRow[0][$value]['main_foto']).'">
					</td>
				</tr>
				';
            }
            $data.=
                '
				</table>
			</div>
			';
        }
        if(strlen($data)>1){
            $datas=
                '
			<h2>Планировки</h2>
			<div data-block="accordion">
			'.
                $data
                .'
			</div>
			<button
		   formaction="https://m16-estate.ru/buildings/'.$label.'"
		   data-background-color="#019cdf"
		   data-color="white"
		   data-primary="true">Посмотреть все планировки</button>
			';
            return $datas;
        }else{
            return '';
        }
    }else{
        return '';
    }
}
function checkImg($path){
    if(file_exists($_SERVER['DOCUMENT_ROOT'].$path)) {
        return 'https://m16-estate.ru'.$path;
    } else {
        return 'https://m16-estate.ru/asset/img/logo-M16.png';
    }
}
function prepSame($label){
    $price=sqlGet('ci_buildings','`price`',"`link`='".$label."'");
    $price=$price[0]['price'];
    if($price<3000000){
        $dataRows[]=sqlGet('ci_buildings','`name`,`link`,`price`,`mainfoto`',"`price`<3000000 AND `link`!='$label'");
    }elseif($price<5000000){
        $dataRows[]=sqlGet('ci_buildings','`name`,`link`,`price`,`mainfoto`',"`price`>3000000 AND `price`<5000000 AND `link`!='$label'");
    }elseif($price<15000000){
        $dataRows[]=sqlGet('ci_buildings','`name`,`link`,`price`,`mainfoto`',"`price`>5000000 AND `price`<15000000 AND `link`!='$label'");
    }else{
        $dataRows[]=sqlGet('ci_buildings','`name`,`link`,`price`,`mainfoto`',"`price`>15000000 AND `link`!='$label'");
    }
    $data=
        '
	<h2 class="h2">Объекты рядом</h2>
	<div data-block="slider">
		<figure>
			<figcaption><a href="https://m16-estate.ru/buildings/'.$dataRows[0][0]['link'].'">'.$dataRows[0][0]['name'].' от '.((int)$dataRows[0][0]['price']/1000000).' млн руб</a></figcaption>
			<a href="https://m16-estate.ru/buildings/'.$dataRows[0][0]['link'].'"><img src="'.$dataRows[0][0]['mainfoto'].'"></a>
		</figure>
		<figure>
			<figcaption><a href="https://m16-estate.ru/buildings/'.$dataRows[0][1]['link'].'">'.$dataRows[0][1]['name'].' от '.((int)$dataRows[0][1]['price']/1000000).' млн руб</a></figcaption>
			<a href="https://m16-estate.ru/buildings/'.$dataRows[0][1]['link'].'"><img src="'.$dataRows[0][1]['mainfoto'].'"></a>
		</figure>
	</div>
	';
    return $data;
}

function prepCall(){
    return
        '
	<form
		data-type="callback"
		data-background-color="#019cdf"
		data-color="white"
		data-primary="true"
		data-send-to="mail@m16-estate.ru"
	</form>
	';
}






function sqlGet($table,$subject,$where){
    $mysqli = new mysqli('localhost', 'ned', '5M2i1R0q', 'ned');
    if ($mysqli->connect_error) {
        die('Ошибка подключения (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
    }

    $mysqli->query("SET NAMES 'utf8mb4'");
    $result=array();
    //echo'<br>';
    if ($data = $mysqli->query("SELECT ".$subject." FROM ".$table." WHERE ".$where." LIMIT 30")) {
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
function sqlGetFull($table,$subject,$where){
    $mysqli = new mysqli('localhost', 'ned', '5M2i1R0q', 'ned');
    if ($mysqli->connect_error) {
        die('Ошибка подключения (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
    }

    $mysqli->query("SET NAMES 'utf8mb4'");
    $result=array();
    //echo("SELECT ".$subject." FROM ".$table." WHERE ".$where);
    //echo'<br>';
    if ($data = $mysqli->query("SELECT ".$subject." FROM ".$table." WHERE ".$where." LIMIT 70 OFFSET 280")) {
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

function metro($id){
    $data=sqlGet('ci_metro','`name`',"`id`='".$id."'");
    return $data[0]['name'];
}

function rayon($id){
    $data=sqlGet('ci_rayon','`name`',"`id`='".$id."'");
    return $data[0]['name'];
}

function builder($id){
    $data=sqlGet('ci_builders','`name`',"`id`='".$id."'");
    return $data[0]['name'];
}

function dataKv($dt, $mini = true) {
    $newTime = strtotime(date('d.m.Y H:i:s'));
    if ($dt < $newTime) {
        $return = 'Сдан';
    } else {
        $ex = explode('.', date('d.m.Y', $dt));

        switch ($ex[1]) {
            case '01':
            case '02':
            case '03':
                $r = 'I';
                break;
            case '04':
            case '05':
            case '06':
                $r = 'II';
                break;
            case '07':
            case '08':
            case '09':
                $r = 'III';
                break;
            case '10':
            case '11':
            case '12':
                $r = 'IV';
                break;
        }

        $return = $r . (($mini) ? ' кв. ' : ' квартал ') . $ex[2];
    }

    return $return;
}
?>