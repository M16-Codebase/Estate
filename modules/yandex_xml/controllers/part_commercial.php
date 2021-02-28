<?php
$sell = false;
$rent = false;

$dateAdd	= $this->gen->getFormatDate($building['date_add']);
$dateEdit	= $this->gen->getFormatDate($building['date_edit']);
$dealStatus = (($building['typeprodazha_id'] == 1)?'прямая продажа':'встречная продажа');
$otdelka    = '';

$img = (mb_strlen($building['main_foto']))?array_merge(array($building['main_foto']),$photos):$photos;

$arr = array();
// Общая информация об объявлении
$offer						= array();
$offer['type']				= 'продажа';															//  Тип сделки («продажа», «аренда»).
$offer['property-type']		= 'коммерческая';														//  Тип недвижимости (рекомендуемое значение — «жилая»).
$offer['category']			= 'помещение';															//* Категория объекта («комната», «квартира», «дом», «участок», «flat», «room», «house», «cottage», «townhouse», «таунхаус», «часть дома», «house with lot», «дом с участком», «дача», «lot», «земельный участок»). Сейчас принимаются объявления только о продаже и аренде жилой недвижимости: квартир, комнат, домов и участков.
$offer['url']				= 'http://m16-estate.ru/commercial/'.$building['link'];	//* URL страницы с объявлением.
$offer['creation-date']		= $dateAdd;																//* Дата создания объявления (в формате YYYY-MM-DDTHH:mm:ss+00:00).
$offer['last-update-date']	= $dateEdit;															//  Дата последнего обновления объявления (в формате YYYY-MM-DDTHH:mm:ss+00:00).

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

if ($latitude	!= '') $offer['location']['latitude']	= $latitude;
if ($longitude	!= '') $offer['location']['longitude']	= $longitude;

// Метро и доп. метро
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
		'value'     => $building['price'],
		'currency'  => 'RUB',
	);

	if ($building['comm_square'] > 0)
	{
		$cond['price_m'] = array(
			'value'		=> round($building['price']/$building['comm_square']),
			'currency'	=> 'RUB',
			'unit'		=> 'кв. м',
		);
	}

	$sell = true;
}

if ((int)$building['price_arenda'] > 0)
{
	$cond['price_arenda'] = array(
		'value'		=> $building['price_arenda'],
		'currency'	=> 'RUB',
		'period'	=> 'месяц',
	);

	if ($building['comm_square'] > 0)
	{
		$cond['price_arenda_m'] = array(
			'value'		=> round($building['price_arenda']/$building['comm_square']),
			'currency'	=> 'RUB',
			'unit'		=> 'кв. м',
			'period'	=> 'месяц',
		);
	}

	$rent = true;
}

$cond['haggle']		= 'нет';									//  Торг (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).
$cond['mortgage']	= ($building['ipoteka'] > 0)?'да':'нет';	//  Ипотека (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0»).
$cond['deal-status']= $dealStatus;

$arr['cond'] = $cond;

// Информация об объекте
$info				= array();
$info['image']		= $img;
$info['description']= $this->gen->getDescription($building);
$info['area']		= $building['comm_square'];

$arr['info'] = $info;

// Описание здания
$obj = array();
$obj['building-type'] = $buildingType;                                    //  Тип дома (рекомендуемые значения — «кирпичный», «монолит», «панельный»).
$arr['obj'] = $obj;

if ($building['price'] > 0 OR $building['price_arenda'] > 0)
{
	$handle = fopen($this->gen->file, 'a');

	if ($sell)
	{
		$arr['offer']['type'] = 'продажа';
		fwrite($handle, $this->load->view('part_commercial', array('id' => $prefix.$building['id'].'_s', 'row' => $arr, 'agent' => $agent, 'sell' => true, 'rent' => false), TRUE));
	}

	if ($rent)
	{
		$arr['offer']['type'] = 'аренда';
		fwrite($handle, $this->load->view('part_commercial', array('id' => $prefix.$building['id'].'_r', 'row' => $arr, 'agent' => $agent, 'rent' => true, 'sell' => false), TRUE));
	}

	fclose($handle);
}

unset($arr);