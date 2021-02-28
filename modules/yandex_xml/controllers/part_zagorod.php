<?php
$type = $this->sprav->getZagorodType($building);

if (!$type)
	return false;

$dateAdd	= $this->gen->getFormatDate($building['date_add']);
$dateEdit	= $this->gen->getFormatDate($building['date_edit']);
$rooms		= 0;
$dealStatus	= (($building['typeprodazha_id'] == 1)?'прямая продажа':'встречная продажа');
$otdelka	= '';

$img = (mb_strlen($building['main_foto']))?array_merge(array($building['main_foto']),$photos):$photos;

$arr = array();
// Общая информация об объявлении
$offer							= array();
$offer['type']					= 'продажа';															//  Тип сделки («продажа», «аренда»).
$offer['property-type']			= 'загородная';														//  Тип недвижимости (рекомендуемое значение — «жилая»).
$offer['category']				= $type;															//* Категория объекта («комната», «квартира», «дом», «участок», «flat», «room», «house», «cottage», «townhouse», «таунхаус», «часть дома», «house with lot», «дом с участком», «дача», «lot», «земельный участок»). Сейчас принимаются объявления только о продаже и аренде жилой недвижимости: квартир, комнат, домов и участков.
$offer['url']					= 'http://m16-estate.ru/zagorod/'.$building['link'];	//* URL страницы с объявлением.
$offer['creation-date']			= $dateAdd;																//* Дата создания объявления (в формате YYYY-MM-DDTHH:mm:ss+00:00).
$offer['last-update-date']		= $dateEdit;															//  Дата последнего обновления объявления (в формате YYYY-MM-DDTHH:mm:ss+00:00).

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
		'value'     => $building['price'],
		'currency'  => 'RUB',
	);
}

$cond['haggle']		= 'нет';								//  Торг (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).
$cond['mortgage']	= ($building['ipoteka'] > 0)?'да':'нет';//  Ипотека (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0»).
$cond['deal-status']= $dealStatus;

$arr['cond'] = $cond;

// Информация об объекте
$info					= array();
$info['image']			= $img;
$info['description']	= $this->gen->getDescription($building);
if ($building['square_dom'] > 0)		$info['area']		= $building['square_dom'];
if ($building['square_uchastok'] > 0)	$info['lot-area']	= $building['square_uchastok'];

$arr['info'] = $info;

// Описание здания
$obj							= array();
$obj['building-name']           = $building['name'];			//  Название жилого комплекса (для Москвы и Санкт-Петербурга — обязательное поле для новостроек).
$obj['building-type']			= $building['material_name'];	//  Тип дома (рекомендуемые значения — «кирпичный», «монолит», «панельный»).

$arr['obj'] = $obj;

// Описание загородной недвижимости
$zag = array();
// $zag['pmg']                      = '';                   //  возможность ПМЖ (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»)
// $zag['toilet']                   = '';                   //  расположение (возможные значения — «в доме», «на улице»)
// $zag['shower']                   = '';                   //  расположение (возможные значения — «в доме», «на улице»)
// $zag['kitchen']                  = '';                   //  Наличие кухни (строго ограниченные значения — «да»/«нет», «true»/ «false», «1»/«0», «+»/«-»)
// $zag['pool']                     = '';                   //  наличие бассейна (строго ограниченные значения — «да»/«нет», «true»/ «false», «1»/«0», «+»/«-»)
// $zag['billiard']                 = '';                   //  наличие бильярда (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»)
// $zag['sauna']                    = '';                   //  наличие сауны/бани (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»)
// $zag['heating-supply']           = '';                   //  наличие отопления (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/-)
// $zag['sewerage-supply']          = '';                   //  канализация (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»)

$zag['water-supply']             = ($building['z_gas'])?1:0;		//  наличие водопровода (строго ограниченные значения — «да»/«нет», « true»/«false», «1»/«0», «+»/«-»)
$zag['electricity-supply']       = ($building['z_water'])?1:0;		//  электроснабжение (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»)
$zag['gas-supply']               = ($building['z_electric'])?1:0;	//  подключение к газовым сетям (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»)

$arr['zag'] = $zag;

if ($building['price'] > 0)
{
	$handle = fopen($this->gen->file, 'a');
	fwrite($handle, $this->load->view('part_zagorod', array('id' => $prefix.$building['id'].'_s', 'row' => $arr, 'agent' => $agent, 'sell' => true, 'rent' => false), TRUE));
	fclose($handle);
}

unset($arr);