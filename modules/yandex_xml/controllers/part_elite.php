<?php
$dateAdd	= $this->gen->getFormatDate($building['date_add']);
$dateEdit	= $this->gen->getFormatDate($building['date_edit']);
$rooms		= (isset($room[$building['room_id']]))?$room[$building['room_id']]:0;
$dealStatus	= (($building['typeprodazha_id'] == 1)?'прямая продажа':'встречная продажа');
$otdelka	= $this->sprav->getOtdelkaName($building['otdelka_id']);
$img = (mb_strlen($building['main_foto']))?array_merge(array($building['main_foto']),$photos):$photos;

if ($rooms == 0)
	return false;

$arr = array();
// Общая информация об объявлении
$offer							= array();
$offer['type']					= 'продажа';											//  Тип сделки («продажа», «аренда»).
$offer['property-type']			= 'жилая';												//  Тип недвижимости (рекомендуемое значение — «жилая»).
$offer['category']				= 'квартира';											//* Категория объекта («комната», «квартира», «дом», «участок», «flat», «room», «house», «cottage», «townhouse», «таунхаус», «часть дома», «house with lot», «дом с участком», «дача», «lot», «земельный участок»). Сейчас принимаются объявления только о продаже и аренде жилой недвижимости: квартир, комнат, домов и участков.
$offer['url']					= 'http://m16-estate.ru/elite/'.$building['link'];	//* URL страницы с объявлением.
$offer['creation-date']			= $dateAdd;												//* Дата создания объявления (в формате YYYY-MM-DDTHH:mm:ss+00:00).
$offer['last-update-date']		= $dateEdit;											//  Дата последнего обновления объявления (в формате YYYY-MM-DDTHH:mm:ss+00:00).

$offer['location'] = array(							//* Набор элементов, описывающих местоположение объекта.
	'country'				=> 'Россия',			//* Страна, в которой расположен объект.
// 'region'					=> '',					//  Регион указанной страны (необязательно для Москвы и Санкт-Петербурга). Для России — название субъекта РФ.
// 'district'				=> '',					//  Район указанного региона. Для России — название района субъекта РФ.
	'locality-name'			=> $building['area'],	//  Название населенного пункта.
	'sub-locality-name'		=> $rayonName,			//  Район населенного пункта.
// 'non-admin-sub-locality'	=> '',					//  Неадминистративный район города или ориентир (список городов, для которых поддерживается этот параметр, уточняйте по адресу info@realty.yandex.ru).
	'address'				=> $building['adress'],	//  Улица и номер дома.
// 'direction'				=> '',					//  Шоссе (только для Москвы).
// 'distance'				=> '',					//  Расстояние по шоссе до МКАД (указывается в километрах).
	'metro'					=> array()
);

if ($latitude != '')	$offer['location']['latitude'] = $latitude;
if ($longitude != '')	$offer['location']['longitude'] = $longitude;

if (isset($subway[$building['metro_id']]))
	$offer['location']['metro'][] = array('name' => $subway[$building['metro_id']]);

if ($building['dop_metro'] != '')
{
	foreach (explode(',', $building['dop_metro']) as $item)
	{
		if (isset($subway[$item]))
			$offer['location']['metro'][] = array('name' => $subway[$item]);
	}
}

$arr['offer'] = $offer;

// Информация об условиях сделки
$cond = array();

if ((int)$building['price'] > 0)
{
	$cond['price'] = array(
		'value'		=> $building['price'],
		'currency'	=> 'RUB',
	);

	if ($building['square_all'] > 0)
	{
		$cond['price_m'] = array(
			'value'		=> round($building['price']/$building['square_all']),
			'currency'	=> 'RUB',
			'unit'		=> 'кв. м',
		);
	}
}

$cond['haggle']		= 'нет';								//  Торг (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).
$cond['mortgage']	= ($building['ipoteka'] > 0)?'да':'нет';//  Ипотека (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0»).
$cond['deal-status']= $dealStatus;

$arr['cond'] = $cond;

// Описание жилого помещения
$floor = $building['floor'];

$floorCurrent	= 0;
$floorTotal		= 0;

if (mb_strpos($building['floor'], '/'))
{
	$tmp = explode('/', $building['floor']);
	$floorCurrent	= $tmp[0];
	$floorTotal		= $tmp[1];
}
else
{
	$floorCurrent = $building['floor'];
}

// Квартира
$flat = array();
$flat['new-flat']				= 'да';					//* Устанавливается, если квартира продается в новостройке (строго ограниченные значения — «да», «true», «1», «+»). Обязательный параметр для новостроек.
$flat['rooms']					= $rooms;				//* Общее количество комнат в квартире.
$flat['rooms-offered']			= $rooms;				//* Для продажи и аренды комнат: количество комнат, участвующих в сделке.
// $flat['open-plan']			= '';					//  Свободная планировка (строго ограниченные значения — «да», «true», «1», «+»).
// $flat['rooms-type']			= '';					//  Тип комнат (рекомендуемые значения — «смежные», «раздельные»).
// $flat['phone']				= '';					//  Наличие телефона (строго ограниченные значения — «да»/ «нет», «true»/ «false», «1»/ «0», «+»/ «-»).
// $flat['internet']			= '';					//  Наличие интернета (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).
// $flat['room-furniture']		= '';					//  Наличие мебели (строго ограниченные значения — «да»/ «нет», «true»/«false», «1»/ «0», «+»/«-»).
// $flat['kitchen-furniture']	= '';					//  Наличие мебели на кухне (строго ограниченные значения — «да»/«нет», « true»/«false», «1»/«0», «+»/«-»).
// $flat['television']			= '';					//  Наличие телевизора (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/ «-»).
// $flat['washing-machine']		= '';					//  Наличие стиральной машины (строго ограниченные значения — «да»/«нет», « true»/ «false», «1»/ «0», «+»/ «-»).
// $flat['refrigerator']		= '';					//  Наличие холодильника (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).
// $flat['floor-covering']		= '';					//  Покрытие пола (рекомендуемые значения — «паркет», «ламинат», «ковролин», «линолеум»).
// $flat['window-view']			= '';					//  Вид из окон (рекомендуемые значения — «во двор», «на улицу»).
if ($floorCurrent) $flat['floor']= $floorCurrent;		//  Этаж.

$arr['flat'] = $flat;

// Информация об объекте
$info					= array();
$info['image']			= $img;																				//  Фотография (может быть несколько тегов). Фотографии планировок следует передавать первым тегом image.
$info['description']	= $this->gen->getDescription($building);											// Описание
$info['renovation']		= $otdelka;																			//  Ремонт (рекомендуемые значения — «евро», «дизайнерский», «черновая отделка», «требует ремонта», «частичный ремонт», «с отделкой», «хороший»).
$info['area']			= $building['square_all'];															//* Общая площадь.
if ($building['square_life']) $info['living-space']	= array_sum(explode('+', $building['square_life']));	//  Жилая площадь (при продаже комнаты — площадь комнаты).
if ($building['square_life']) $info['room-space']	= explode('+', $building['square_life']);				//  Площадь комнаты (может быть несколько тегов).
if ($building['square_cook']) $info['kitchen-space']= $building['square_cook'];								//  Площадь кухни.
$arr['info'] = $info;

// Описание здания
$obj							= array();
$obj['floors-total']			= ($building['floors'] > 0)?$building['floors']:$floorTotal;	//  Общее количество этажей в доме (обязательное поле для новостроек).
$obj['building-name']			= $building['name'];											//  Название жилого комплекса (для Москвы и Санкт-Петербурга — обязательное поле для новостроек).
$obj['building-type']			= $buildingType;												//  Тип дома (рекомендуемые значения — «кирпичный», «монолит», «панельный»).
// $obj['building-series']		= '';															//  Серия дома.
// $obj['building-phase']		= '';															//  Очередь строительства («очередь 1», «II очередь», «3», ...)
// $obj['building-section']		= '';															//  Корпус дома («корпус 1», «корпус А», «дом 3»).
// $obj['building-state']		= $builtState;													//  Стадия строительства дома — параметр для новостроек (обязательное поле для Москвы и Санкт-Петербурга) (строго ограниченные значения: «unfinished» — строится; «built» — дом построен, но не сдан; «hand-over» — сдан в эксплуатацию). Если значение «built-year» и «ready-quarter» указаны в прошлом времени, для тэга «building-state» следует передавать значение «hand-over».
// $obj['built-year']			= $builtYear;													//  Год постройки. Для новостроек — год сдачи (год необходимо указывать полностью, например, 1996, а не 96). Для Москвы и Санкт-Петербурга — обязательное поле для новостроек.
// $obj['ready-quarter']		= $builtQuarter;												//  Для новостроек — квартал сдачи дома (строго ограниченные значения — «1», «2», «3», «4»). Обязательное поле для Москвы и Санкт-Петербурга.
// $obj['lift']					= '';															//  Наличие лифта (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).
// $obj['rubbish-chute']		= '';															//  Наличие мусоропровода (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).
$obj['is-elite']				= 'да';															//  Элитность (строго ограниченные значения — «да»/«нет», « true»/«false», «1»/«0», «+»/«-»).
// $obj['parking']				= '';															//  Наличие парковки (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).
// $obj['alarm']				= '';															//  Наличие охраны/сигнализации (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).
// $obj['ceiling-height']		= $row['height'];												//  Высота потолков.
$arr['obj'] = $obj;

if ((int)$building['price'] > 0)
{
	$handle = fopen($this->gen->file, 'a');
	fwrite($handle, $this->load->view('part_elite', array('id' => $prefix.$building['id'], 'row' => $arr, 'agent' => $agent), TRUE));
	fclose($handle);
}

unset($arr);