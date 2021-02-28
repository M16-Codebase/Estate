<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Модуль: Зарубежная недвижимость
 **/

class yandex_xml extends MY_Controller {

	private $conf; // конфиг файл
	private $lang; // языковый файл    

	function __construct()
	{
		// конструктор
		parent::__construct();

		include(MDPATH.'yandex_xml/moduleinfo.php');

		$this->module = $moduleinfo['name'];

		// $this->table = $moduleinfo['table'];

		// определяем, нужно ли использовать роутер
		if (!empty($moduleinfo['router']))
		{
			$this->link = $moduleinfo['router'];
		}
		else
		{
			$this->link = $this->module;
		}
	}

	/** Главная */
	function index()
	{
		$this->load->model($this->module.'_gen', 'gen');
		$this->load->model($this->module.'_sprav', 'sprav');

		// Справочники
		$subway			= $this->sprav->getSubwayList();
		$rayon			= $this->sprav->getRayonList();
		$room			= $this->sprav->getRoomList();
		$type			= $this->sprav->getTypeList();
		$typeprodazha	= $this->sprav->getTypeprodazhaList();
		$typesdelka		= $this->sprav->getTypesdelkaList();
		$categorys = array(
			'new'		=> $this->gen->getNewList(),
			'resale'	=> $this->gen->getResaleList(),
			'elite'		=> $this->gen->getEliteList(),
			'zagorod'	=> $this->gen->getZagorodList(),
			'commercial'=> $this->gen->getCommercialList(),
		);

		if ($_GET['test'] == 'run')
		{
			pf($categorys);
			exit;
		}

		$this->gen->domain = 'http://m16-estate.ru';

		$agent = "\t\t<sales-agent>
		\t<name>М16-Недвижимость</name>
		\t<phone>+7(812)688-88-85</phone>
		\t<category>агентство</category>
		\t<organization>Агентство недвижимости Вячеслава Малафеева «М16-Недвижимость».</organization>
		\t<url>{$this->gen->domain}</url>
		\t<email>mail@m16.bz</email>
		\t<photo>{$this->gen->domain}/asset/assets/img/m16-logo.png</photo>
		</sales-agent>";

		// Генерация yandex xml
		$fileName			= 'yandex.xml';
		$dir				= 'asset/xml';
		$this->gen->file	= $dir.'/'.$fileName;

		unlink($this->gen->file);

		if (!is_dir($dir))
			mkdir($dir);

		$this->gen->appendHead();

		foreach ($categorys as $category => $data)
		{
			$prefix = $this->gen->config[$category]['prefix'];
			
			foreach ($data as $building)
			{
				// [prepare]
				$coord			= explode('|', $building['map']);
				$latitude		= (count($coord) == 2)?trim($coord[0]):false;
				$longitude		= (count($coord) == 2)?trim($coord[1]):false;
				$rayonName		= (isset($rayon[$building['rayon_id']]))?$rayon[$building['rayon_id']]:false;
				$subwayName		= (isset($subway[$building['metro_id']]))?$subway[$building['metro_id']]:false;
				$builtYear		= date('Y', $building['korpus_value']);
				$builtQuarter	= get_quarter_num($building['korpus_value']);
				$builtState		= ($building['korpus_value'] < time())?'hand-over':'unfinished';
				$buildingType	= (isset($type[$building['type_id']]))?mb_strtolower($type[$building['type_id']]):'';
				
				$tmp = unserialize($building['foto']);
				$photos = $tmp['foto'];

				switch ($category)
				{
					case 'new':	// 0
						include(MDPATH.'yandex_xml/controllers/part_new.php');
						break;

					case 'resale':	// 1
						include(MDPATH.'yandex_xml/controllers/part_resale.php');
						break;

					case 'zagorod':	// 2
						include(MDPATH.'yandex_xml/controllers/part_zagorod.php');
						break;

					case 'elite':	// 3
						include(MDPATH.'yandex_xml/controllers/part_elite.php');
						break;

					case 'commercial':	// 4
						include(MDPATH.'yandex_xml/controllers/part_commercial.php');
						break;
				}
			}
		}

		$this->gen->appendFooter();
		$this->gen->download();
	}
}
/* End of file */