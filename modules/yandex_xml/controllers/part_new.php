<?php

$this->db->where('banned','0');
$this->db->where('novostroy_id', $building['id']);
$result = $this->db->get('apartments')->result_array();

foreach ($result as $row)
{
	// [prepare]
	// Даты
	$dateAdd	= date('Y-m-d', $row['date_add']).'T'.date('H:i:s', $row['date_add']).'+04:00';
	$dateEdit	= date('Y-m-d', $row['date_edit']).'T'.date('H:i:s', $row['date_edit']).'+04:00';
	$rooms		= (isset($room[$row['room_id']]))?$room[$row['room_id']]:0;
	$dealStatus	= ($building['fz214'] > 0)?'214 ФЗ':(($building['typeprodazha_id'] == 1)?'прямая продажа':'встречная продажа');
	$balcons	= ($row['square_balcony'] == 0)?'нет':count(explode('+', $row['square_balcony']));

	$otdelka	= $this->sprav->getOtdelkaName($row['otdelka_id']);
	$watercloset= $this->sprav->getWcName(explode('+', $row['square_watercloset']));

	$img = (mb_strlen($row['main_foto']))?array_merge(array($row['main_foto']),$photos):$photos;

	$arr = array();
	// Общая информация об объявлении
	$offer							= array();
	$offer['type']					= 'продажа';															//  Тип сделки («продажа», «аренда»).
	$offer['property-type']			= 'жилая';																//  Тип недвижимости (рекомендуемое значение — «жилая»).
	$offer['category']				= 'квартира';															//* Категория объекта («комната», «квартира», «дом», «участок», «flat», «room», «house», «cottage», «townhouse», «таунхаус», «часть дома», «house with lot», «дом с участком», «дача», «lot», «земельный участок»). Сейчас принимаются объявления только о продаже и аренде жилой недвижимости: квартир, комнат, домов и участков.
	$offer['url']					= 'http://m16-estate.ru/buildings/'.$building['link'].'/'.$row['link'];	//* URL страницы с объявлением.
	$offer['creation-date']			= $dateAdd;																//* Дата создания объявления (в формате YYYY-MM-DDTHH:mm:ss+00:00).
	$offer['last-update-date']		= $dateEdit;															//  Дата последнего обновления объявления (в формате YYYY-MM-DDTHH:mm:ss+00:00).
	// $offer['expire-date']		= $dateExp;																//  Дата и время, до которых объявление актуально (в формате YYYY-MM-DDTHH:mm:ss+00:00).
	// $offer['payed-adv']			= 'нет';																//  Признак оплаченного объявления (только для досок объявлений). Строго ограниченные значения: «да» или «нет»; true» или «false»; «1» или «0»; «+» или «-».
	// $offer['manually-added']		= 'нет';																//  Признак объявления, добавленного вручную (только для досок объявлений). Строго ограниченные значения: «да» или «нет»; «true» или «false»; «1» или «0»; «+» или «-».
	
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
		'latitude'				=> $latitude,			//  Географическая широта.
		'longitude'				=> $longitude,			//  Географическая долгота.
		'metro' => array(								//  Ближайшая станция метро (если таковых несколько, каждая должна быть указана в отдельном элементе).
			'name'				=> $subwayName,			//  Название станции метро.
			// 'time-on-transport'	=> '',				//  Время до метро в минутах на транспорте.
			// 'time-on-foot'		=> '',				//  Время до метро в минутах пешком.
			// 'railway-station'	=> '',				//  Ближайшая ж/д станция (для загородной недвижимости).
		),
	);

	$arr['offer'] = $offer;

	// Информация об условиях сделки
	$cond = array();
	$cond['price'] = array(												//* Информация о стоимости.
		'value'		=> $row['price'],									//* Цена (сумма указывается без пробелов).
		'currency'	=> 'RUB',											//* Валюта, в которой указана цена. Поддерживаемые значения: «RUR» или «RUB» — российский рубль;
	);
	
	if ($row['square_all'] > 0)
	{
		$cond['price_m'] = array(
			'value'		=> round($row['price']/$row['square_all']),
			'currency'	=> 'RUB',
			'unit'		=> 'кв. м',
		);
	}

	// $cond['not-for-agents']	= '';									//  Просьба агентам не звонить (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).
	$cond['haggle']				= 'нет';								//  Торг (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).
	$cond['mortgage']			= ($building['ipoteka'] > 0)?'да':'нет';//  Ипотека (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0»).
	// $cond['prepayment']		= '';									//  Предоплата (указывается числовое значение в процентах без знака %).
	// $cond['rent-pledge']		= '';									//  Залог (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).
	// $cond['agent-fee']		= '';									//  Комиссия агента (указывается числовое значение в процентах без знака %).
	$cond['deal-status']		= $dealStatus;							//  Тип сделки. Возможные значения: «прямая продажа», «встречная продажа», «размен», «214 ФЗ», «переуступка». Внимание! Если параметр отсутствует, все объявления партнера в новостройках считаются квартирами от застройщика. Если квартира продается по переуступке, значением deal-status является «переуступка». Для вторичной недвижимости следует отмечать тип сделки — «прямая продажа», «встречная продажа» или «размен».
	// $cond['with-pets']		= '';									//  Для аренды: можно ли с животными (строго ограниченные значения — «да»/ «нет», «true»/«false», «1»/«0», «+»/«-»).
	// $cond['with-children']	= '';									//  Для аренды: можно ли с детьми (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).

	$arr['cond'] = $cond;

	// Информация об объекте
	$info					= array();
	$info['image']			= $img;																						//  Фотография (может быть несколько тегов). Фотографии планировок следует передавать первым тегом image.
	$info['renovation']		= $otdelka;																					//  Ремонт (рекомендуемые значения — «евро», «дизайнерский», «черновая отделка», «требует ремонта», «частичный ремонт», «с отделкой», «хороший»).
	// $info['quality']		= '';																						//  Состояние объекта (рекомендуемые значения — «хорошее», «отличное», «нормальное», «плохое»).
	// $info['description']	= '<![CDATA['.(($building['xml_text'] != '')?$building['xml_text']:$building['xml']).']]>';	//  Дополнительная информация (описание в свободной форме, оставленное подателем объявления).
	$info['description']	= strip_tags(trim(($building['xml_text'] != '')?$building['xml_text']:$building['text']));	//  Дополнительная информация (описание в свободной форме, оставленное подателем объявления).
	$info['area']			= $row['square_all'];																		//* Общая площадь.
	$info['living-space']	= array_sum(explode('+', $row['square_life']));												//  Жилая площадь (при продаже комнаты — площадь комнаты).
	$info['room-space']		= explode('+', $row['square_life']);														//  Площадь комнаты (может быть несколько тегов).
	$info['kitchen-space']	= $row['square_cook'];																		//  Площадь кухни.
	// $info['value']		= '';																						//  Площадь (числовое значение).
	// $info['unit']		= 'кв. м';																					//  Единица площади (рекомендуемые значения — «кв. м», «sq.m»).
	// $info['lot-area']	= '';																						//  Площадь участка в случае предложения «дом с участком» или «участок».
	// $info['lot-type']	= '';																						//  Тип участка (рекомендуемые значения — «ИЖC», «садоводство»).
	$arr['info'] = $info;


	// Описание жилого помещения
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
	$flat['balcony']				= $balcons;				//  Тип балкона (рекомендуемые значения — «балкон», «лоджия», «2 балкона», «2 лоджии»).
	$flat['bathroom-unit']			= $watercloset;			//  Тип санузла (рекомендуемые значения — «совмещенный», «раздельный», «2»).
	// $flat['floor-covering']		= '';					//  Покрытие пола (рекомендуемые значения — «паркет», «ламинат», «ковролин», «линолеум»).
	// $flat['window-view']			= '';					//  Вид из окон (рекомендуемые значения — «во двор», «на улицу»).
	$flat['floor']					= $row['floor'];		//  Этаж.
	
	$arr['flat'] = $flat;

	// Описание здания
	$obj							= array();
	$obj['floors-total']			= ($building['floors'] > 0)?$building['floors']:0;	//  Общее количество этажей в доме (обязательное поле для новостроек).
	$obj['building-name']			= $building['name'];								//  Название жилого комплекса (для Москвы и Санкт-Петербурга — обязательное поле для новостроек).
	$obj['yandex-building-id']		= $building['id_yandex'];							//  Идентификатор жилого комплекса в базе данных Яндекса — параметр только для новостроек (обязательное поле для Москвы и Санкт-Петербурга). Идентификатор можно получить на сервисе — из url карточки жилого комплекса. Полный список идентификаторов yandex-building-id предоставляется по запросу на info@realty.yandex.ru.
	$obj['building-type']			= $buildingType;									//  Тип дома (рекомендуемые значения — «кирпичный», «монолит», «панельный»).
	// $obj['building-series']		= '';												//  Серия дома.
	// $obj['building-phase']		= '';												//  Очередь строительства («очередь 1», «II очередь», «3», ...)
	// $obj['building-section']		= '';												//  Корпус дома («корпус 1», «корпус А», «дом 3»).
	$obj['building-state']			= $builtState;										//  Стадия строительства дома — параметр для новостроек (обязательное поле для Москвы и Санкт-Петербурга) (строго ограниченные значения: «unfinished» — строится; «built» — дом построен, но не сдан; «hand-over» — сдан в эксплуатацию). Если значение «built-year» и «ready-quarter» указаны в прошлом времени, для тэга «building-state» следует передавать значение «hand-over».
	$obj['built-year']				= $builtYear;										//  Год постройки. Для новостроек — год сдачи (год необходимо указывать полностью, например, 1996, а не 96). Для Москвы и Санкт-Петербурга — обязательное поле для новостроек.
	$obj['ready-quarter']			= $builtQuarter;									//  Для новостроек — квартал сдачи дома (строго ограниченные значения — «1», «2», «3», «4»). Обязательное поле для Москвы и Санкт-Петербурга.
	// $obj['lift']					= '';												//  Наличие лифта (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).
	// $obj['rubbish-chute']		= '';												//  Наличие мусоропровода (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).
	// $obj['is-elite']				= '';												//  Элитность (строго ограниченные значения — «да»/«нет», « true»/«false», «1»/«0», «+»/«-»).
	// $obj['parking']				= '';												//  Наличие парковки (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).
	// $obj['alarm']				= '';												//  Наличие охраны/сигнализации (строго ограниченные значения — «да»/«нет», «true»/«false», «1»/«0», «+»/«-»).
	$obj['ceiling-height']			= $row['height'];									//  Высота потолков.
	$arr['obj'] = $obj;

	if ($row['price'] > 0 AND $info['living-space'] > 0 AND $rooms > 0)
	{
		$handle = fopen($this->gen->file, 'a');
		fwrite($handle, $this->load->view('part_new', array('id' => $prefix.$row['id_room'], 'row' => $arr, 'agent' => $agent), TRUE));
		fclose($handle);
	}
	unset($arr);
}