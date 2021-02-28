<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модуль: Новости
**/
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
class news extends MY_Controller {

	private $conf; // конфиг файл
    private $lang; // языковый файл

    function __construct()
	{
		// конструктор
		parent::__construct();

        include(MDPATH.'news/moduleinfo.php');
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];

        // определяем, нужно ли использовать роутер
        if(!empty($moduleinfo['router']))
            { $this->link = $moduleinfo['router']; }
        else
            { $this->link = $this->module; }
	}

    /** Роутер модуля */
    function _remap($method, $argument)
    {
        if($moduleinfo['status'] != 0) // проверка на доступность модуля
            { return false; }
        else
        {
            if(isset($argument[0]))  $u = $argument[0]; else $u = 0; // сегмент ссылки
            if(method_exists($this,$method)) // если существует метод, то запускаем его
            {
                if($method == 'index') { $this->index(); } else
                if($method == 'tag') { $this->tag(); } else
				if($method == 'cat') { $this->cat(); } else
                if($method == 'limit') { $this->limit($u); } else
                if($method == 'limits') { $this->limits($u); } else
                if($method == 'search') { $this->searchFunction($u); } else
                { show_404('Метода не существует: '. $this->uri->uri_string()); }
            }
            else
            {
                if (
                    !is_bool(strpos($_SERVER['REQUEST_URI'], '/news/'))
                    && strpos($_SERVER['REQUEST_URI'], '/amp')
                    && array_count_values(explode('/', $_SERVER['REQUEST_URI']))['amp'] < 2
                    //&& ($_SERVER['REMOTE_ADDR'] == '46.47.225.222'
                    //&& $_SERVER['REMOTE_ADDR'] == '78.37.56.52')
                ) {
                    $this->view(true);
                } else {
                    if (!empty($argument)) {
                        show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
                        return;
                    }
                    if (is_numeric($method)) // если идет пагинация
                    {
                        $this->index($method);
                    } else // иначе просмотр конкретной записи
                    {
                        $this->view();
                    }
                }
            }
        }
    }

    /** Опции модуля */
    function setOptions()
    {
        // загрузка настроек
        $this->conf = $this->load->config($this->module.'/'.$this->module, true);

        // загрузка языка
        $foreach = $this->load->language($this->table.'_mod', '', true);
        foreach($foreach as $key=>$l) { $this->lang->$key = htmlspecialchars_decode($l['name']); }

        // выводить или не выводить хлебные крошки для модуля
        $this->noBreadcrumbs = $this->conf['breadcrumbs'];
    }
    function AmpPage($data){
        error_reporting( E_ALL );
        $page=array();
        $page['canonical']='https://m16-estate.ru/news/'.$data['link'];
        $page['mainlink']=$data['link'];
        $page['favicon']='/favicon.ico';
        $page['title']=$data['title'];
        $page['name']=$data['name'];
        $page['description']=$data['description'];
        $page['date']=$data['date'];
        $page['mainphoto']=$data['mainfoto'];
        $page['content']=$data['text'];
        $page['content']=$this->handleImages($page['content']);
        $page['content']=$this->handleBlockquotes($page['content']);
        $page['content']=$this->handleTables($page['content']);
        //echo'<pre>';print_r($data);echo'</pre>';
        //echo'<pre>';print_r($page);echo'</pre>';
        include('/var/www/estate/data/www/m16-estate.ru/releases/20151208131140/modules/news/views/amp-blog/amp.php');
    }
    function handleTables($inData){
        while(strpos($inData,'<table class="table_blog"')){
            $imgstr=substr($inData,strpos($inData,'<table'));
            $divend=strpos($imgstr,'</table>');
            $imgstr=substr($imgstr,0,$divend);
            $inData=str_replace($imgstr,'<div class="wrapTable">'.str_replace('class="table_blog"','class="table_blog checked-data"',$imgstr).'</div>',$inData);
        }
        return $inData;
    }
    function handleImages($inData){
        while(strpos($inData,'<div class="blog-img">')){
            $imgstr=substr($inData,strpos($inData,'<div class="blog-img">'));
            $divend=strpos($imgstr,'</div>');
            $imgstr=substr($imgstr,0,$divend);
            $inData=str_replace($imgstr,$this->wrapImage($imgstr),$inData);
        }
        return $inData;
    }
    function wrapImage($inp){
        $urlstart=strpos($inp,'src="/')+5;
        $per=substr($inp,$urlstart);
        $urlend=strpos($per,'" />');
        $url=substr($inp,$urlstart,$urlend);
        $subscr=substr($inp,strpos($inp,'<p>')+3,strpos($inp,'</p>'));
        $data=
        '
        <figure class="ampstart-image-with-caption m0 relative mb4" style="width: 100%; padding: 0; margin: 0;"> <!-- картинка начало -->
          <amp-img src="'.$url.'" width="820" height="540" layout="responsive" class="" alt="'.$subscr.'"></amp-img>
          <figcaption class="h5 mt1 px3">
            '.$subscr.'
          </figcaption>
        </figure>
        ';
        return $data;
    }
    function handleBlockquotes($inData){
        while(strpos($inData,'<blockquote>')){
            $inData=str_replace('<blockquote>','<blockquote class="ampstart-pullquote" cite="http://example.org">',$inData);
        }
        return $inData;
    }

	/** Главная */
	function index($offset = 0)
	{
        $this->setOptions(); // заносим опции в переменные

        $model = 'news_model';
        $this->load->model($this->module.'/'.$model, $model);



        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;
        $data['conf']['perPaging'] = 30;

        $dataRow = $this->$model->pagination_tag($offset); // вытаскиваем данные
        if($dataRow) // проверяем есть ли данные
        {
            $data['razdel'] = $this->load->module('category')->infoPage(array('banned'=>'0'),array('name','link'));

            // проверяем нужно ли использовать пагинацию
            $arrayPagination = array(
                'all_count' => $this->$model->all_count(),
                'conf' => $data['conf'],
                'noAjax' => true,
                'uri' => $this->module,
                'uri_segment' => 2,
                'num_links' => 2
            );
            $data['pagination'] = $this->paginations($arrayPagination);
            // проходим цикл для формирования данных
            foreach($dataRow as $key=>$value)
            {
                $tags = explode(',',$value['tag']);
                $tag = array();
				if(!empty($tags)) {
	                foreach($tags as $tg)
	                {
	                    $g = trim($tg);
	                    if(!empty($g)) {
	                    	$tag[] = '<a href="/news/tag/'.$g.'">'.trim($g).'</a>';
						}
	                }
				}

                $razdels = explode(',',$value['category']);
                $razdel = array();
                foreach($razdels as $rz)
                {
                    $razdel[] = trim($data['razdel'][$rz]['name']);
                }

                $dates = date('d.m.Y', $value['date']);
                if($dates == '01.01.1970') { $d = explode('-',$value['date']); $dates = $d[2].'.'.$d[1].'.'.$d[0]; }

                $data['rows']->$key->name = $value['name'];
				if(!preg_match("|no_image|", $value['mainfoto'])) {
					$data['rows']->$key->foto = $value['mainfoto'];
				}else{
					$data['rows']->$key->foto = "/asset/uploads/images/news/placeholder.jpg";
				}
				
				
				if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$value['mainfoto'])) {
					$data['rows']->$key->foto = "/asset/uploads/images/news/placeholder.jpg";
				}
				
                $data['rows']->$key->link = BASEURL.'/'.$this->link.'/'.$value['link'];
                $data['rows']->$key->text = $value['shorting'];
                $data['rows']->$key->override = $value['override'];
                $data['rows']->$key->date = $dates;
                $data['rows']->$key->tag = empty($tag) ? "" : implode(', ',$tag);
                $data['rows']->$key->razdel = implode(', ',$razdel);
            }
        } else {
            show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
            return;
        }

        // генерируем title, keywords, description
        $this->addVar('title', $this->lang->md_title);
        $this->addVar('keywords', $this->lang->md_keywords);
        $this->addVar('description', $this->lang->md_description);

        // задаем хлебную кроху
        if(!empty($this->lang->md_breadcrumbs))
            { $this->breadcrumbs($this->lang->md_breadcrumbs); }
        else
            { $this->breadcrumbs($this->lang->md_header); }
		$this->addVar('pushcode', '<script type="text/javascript">
                    _hcwp = window._hcwp || [];
                    _hcwp.push({widget:"Stream", widget_id: 21825});
                    (function() {
                    if("HC_LOAD_INIT" in window)return;
                    HC_LOAD_INIT = true;
                    var lang = (navigator.language || navigator.systemLanguage || navigator.userLanguage || "en").substr(0, 2).toLowerCase();
                    var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true;
                    hcc.src = ("https:" == document.location.protocol ? "https" : "http")+"://w.hypercomments.com/widget/hc/21825/"+lang+"/widget.js";
                    var s = document.getElementsByTagName("script")[0];
                    s.parentNode.insertBefore(hcc, s.nextSibling);
                    })();
                </script>');
        $this->addVar('template', $this->render('tmpl/news', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
	}

    /**
	* Тег
	**/
	function tag()
	{
        $data['razdel'] = $this->load->module('category')->infoPage(array('banned'=>'0'),array('name','link'));

        $tg = urldecode(uri(3));

        $this->setOptions(); // заносим опции в переменные

        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        $dataRow = $this->$model->pagination_tag(uri(4),$tg); // вытаскиваем данные
        if($dataRow) // проверяем есть ли данные
        {
            $data['razdel'] = $this->load->module('category')->infoPage(array('banned'=>'0'),array('name','link'));

            // проверяем нужно ли использовать пагинацию
            $arrayPagination = array(
                'all_count' => $this->$model->all_count(false, '', array('tag' => $tg)),
                'conf' => $data['conf'],
                'noAjax' => true,
                'uri' => $this->module.'/tag/'.uri(3),
                'uri_segment' => 4,
                'num_links' => 2
            );
            $data['pagination'] = $this->paginations($arrayPagination);
            // проходим цикл для формирования данных
            foreach($dataRow as $key=>$value)
            {
                $tags = explode(',',$value['tag']);
                $tag = array();
                if(!empty($tags)) {
	                foreach($tags as $tg)
	                {
	                    $g = trim($tg);
	                    if(!empty($g)) {
	                    	$tag[] = '<a href="/news/tag/'.$g.'">'.trim($g).'</a>';
						}
	                }
				}

                $razdels = explode(',',$value['category']);
                $razdel = array();
                foreach($razdels as $rz)
                {
                    $razdel[] = trim($data['razdel'][$rz]['name']);
                }

                $dates = date('d.m.Y', $value['date']);
                if($dates == '01.01.1970') { $d = explode('-',$value['date']); $dates = $d[2].'.'.$d[1].'.'.$d[0]; }

                $data['rows']->$key->name = $value['name'];
                $data['rows']->$key->link = BASEURL.'/'.$this->link.'/'.$value['link'];
                $data['rows']->$key->foto = $value['mainfoto'];
                $data['rows']->$key->text = $value['shorting'];
                $data['rows']->$key->date = $dates;
                $data['rows']->$key->tag = empty($tag) ? "" : implode(', ',$tag);
                $data['rows']->$key->razdel = implode(', ',$razdel);
            }
        }

        // генерируем title, keywords, description
        $this->addVar('title', $this->lang->md_title);
        $this->addVar('keywords', $this->lang->md_keywords);
        $this->addVar('description', $this->lang->md_description);

        // задаем хлебную кроху
        if(!empty($this->lang->md_breadcrumbs))
            { $this->breadcrumbs($this->lang->md_breadcrumbs); }
        else
            { $this->breadcrumbs($this->lang->md_header); }

        $this->addVar('template', $this->render('tmpl/news', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
	}

	/**
	* Категория
	**/
	function cat()
	{
        $data['razdel'] = $this->load->module('category')->infoPage(array('banned'=>'0'),array('name','link'));

        $tg = $this->uri->segments[3];
        $pageIndex = isset($this->uri->segments[4]) ? intval($this->uri->segments[4]) : 0;

        $this->db->where('link', $tg);
		$q_cat = $this->db->get('ncategory');
		$r_cat = $q_cat->row_array();



	  	if(empty($r_cat['name']) || !is_int($pageIndex) || isset($this->uri->segments[5])) {
            $this->_view404();
		}
		/*
		if(empty($r_cat['name']) || empty($pageIndex) || isset($this->uri->segments[5])) {
            $this->_view404();
		}
		*/

        $this->setOptions(); // заносим опции в переменные

		$data['bread'] = '<ul class="breadcrumbs">';
    	$data['bread'] .= '<li><a href="/">Главная</a></li>';
    	//$data['bread'] .= '<li><a href="/news">Новости</a></li>';
    	$data['bread'] .= '<li><a href="#" class="no-hover">'.$r_cat['name'].'</a></li>';
     	$data['bread'] .= '</ul>';

        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        $dataRow = $this->$model->pagination_cat($pageIndex, $r_cat['id']); // вытаскиваем данные

        if($dataRow) // проверяем есть ли данные
        {
            $data['razdel'] = $this->load->module('category')->infoPage(array('banned'=>'0'),array('name','link'));

            // проверяем нужно ли использовать пагинацию
            $arrayPagination = array(
                'all_count' => $this->$model->all_count(false, '', array('ncategory' => $r_cat['id'])),
                'conf' => $data['conf'],
                'noAjax' => true,
                'uri' => $this->module.'/cat/' . $tg,
                'uri_segment' => 4,
                'num_links' => 2
            );
            $data['pagination'] = $this->paginations($arrayPagination);
            // проходим цикл для формирования данных
            foreach($dataRow as $key=>$value)
            {
                $tags = explode(',',$value['tag']);
                $tag = array();
                if(!empty($tags)) {
	                foreach($tags as $tg)
	                {
	                    $g = trim($tg);
						if(!empty($g)) {
	                    	$tag[] = '<a href="/news/tag/'.$g.'">'.trim($g).'</a>';
						}
	                }
				}

                $razdels = explode(',',$value['category']);
                $razdel = array();
                foreach($razdels as $rz)
                {
                    $razdel[] = trim($data['razdel'][$rz]['name']);
                }

                $dates = date('d.m.Y', $value['date']);
                if($dates == '01.01.1970') { $d = explode('-',$value['date']); $dates = $d[2].'.'.$d[1].'.'.$d[0]; }

                $data['rows']->$key->name = $value['name'];
                $data['rows']->$key->link = BASEURL.'/'.$this->link.'/'.$value['link'];
                $data['rows']->$key->foto = $value['mainfoto'];
                $data['rows']->$key->text = $value['shorting'];
                $data['rows']->$key->date = $dates;
                $data['rows']->$key->tag = empty($tag) ? "" : implode(', ',$tag);
                $data['rows']->$key->razdel = implode(', ',$razdel);
            }
        }

        // генерируем title, keywords, description
        $this->addVar('title', $r_cat['title']);
        $this->addVar('keywords', $r_cat['keywords']);
        $this->addVar('description', $r_cat['description']);
        $this->addVar('canonical_link', site_url('/news'));

        // задаем хлебную кроху
        if(!empty($this->lang->md_breadcrumbs))
            { $this->breadcrumbs($this->lang->md_breadcrumbs); }
        else
            { $this->breadcrumbs($this->lang->md_header); }

        $this->addVar('template', $this->render('tmpl/news', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
	}

    /** Просмотр одной записи */
    function view($amp=false)
    {
        $this->setOptions(); // заносим опции в переменные

        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        $dataRow = $this->$model->getRow($this->data['uri2']); // вытаскиваем данные

        if (empty($dataRow)) {
            // Если в url есть amp не показывать страницу
            if (strpos($_SERVER['REQUEST_URI'], '/amp')) {
                show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
                return;
            }
            // если записине нашлось, то пробуем найти по районам
            // и вывести район
            show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
            //$this->rayonPage($this->data['uri2']);
            return;
        }
        if(!empty($dataRow))
        {
            if($amp){
                $this->AmpPage($dataRow);
                exit;
            }
                // $this->viewSession($dataRow['id']); // заносим в сессию ид

                $data['rows']->header = $dataRow['name'];
                $data['rows']->text = $dataRow['text'];
                $data['rows']->ratval = $dataRow['rating'];
                $data['rows']->raterval = $dataRow['raters'];

                $dates = date('d.m.Y', $dataRow['date']);
                if($dates == '01.01.1970') { $d = explode('-',$dataRow['date']); $dates = $d[2].'.'.$d[1].'.'.$d[0]; }
                $data['rows']->date = $dates;

                // $this->$model->module_edit($dataRow['id'], array('views' => ($dataRow['views'] + 1)));
            }
            else{
                header('Location: /404//',301);
                exit;
            }


		$ncategory = '';
		$explode = explode(',', $dataRow['ncategory']);

		if(!empty($explode)) {
			$explode = $explode[0];
			$this->db->select('link, name');
			$this->db->where('id', $explode);
			$q = $this->db->get('ncategory');
			$res_ncategory = $q->row();

			if(!empty($res_ncategory)) {
				$ncategory = '<li><a href="/news/cat/'.$res_ncategory->link.'">'.$res_ncategory->name.'</a></li>';
			}
		}


        $data['bread'] = '<ul class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">';
    	$data['bread'] .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="/"><span itemprop="name">Главная</span></a>
<meta itemprop="position" content="1" />
</li>';
    	//$data['bread'] .= '<li><a href="/news">Новости</a></li>';
        if(strpos($ncategory,'Новости')){
            $brcrcat='<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="/news/cat/novosti"><span itemprop="name">Новости</span>
</a><meta itemprop="position" content="2" />
</li>';
$data['rows']->cat =1;
        }else{
            $brcrcat='<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<a itemprop="item" href="/news/cat/stati"><span itemprop="name">Статьи</span>
</a><meta itemprop="position" content="2" />
</li>';
$data['rows']->cat =0;
        }
    	$data['bread'] .= $brcrcat;
    	$data['bread'] .= '<li><a href="#" class="no-hover">'.$dataRow['name'].'</a></li>';
     	$data['bread'] .= '</ul>';
		

        // генерируем title, keywords, description
        $this->addVar('title', $dataRow['title']);
        $this->addVar('keywords', $dataRow['keywords']);
        $this->addVar('description', $dataRow['description']);
		$data['rows']->tag = $dataRow['tag'];
        $data['rows']->author = $dataRow['author'];
        $data['rows']->estate = 'Тут список';

        $site_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        $size = getimagesize($site_url . $dataRow['mainfoto']);
        $w = $size[0];
        $h = $size[1];

        $og['title'] = $dataRow['title'];
        $og['description'] = $dataRow['description'];
        $og['image'] = $dataRow['mainfoto'];
        $og['width'] = $w;
        $og['height'] = $h;

        $this->addVar('OG', $og);
		//echo $dataRow['rating'];
		//echo $dataRow['raters'];
		$this->addVar('ratval', $dataRow['rating']);
		$this->addVar('raterval', $dataRow['raters']);

		$dt = new DateTime($dataRow['date']);
        $date = $dt->format('Y-m-d');
        $time = $dt->format("h:i:s");
        $article_published = $date.'T'.$time.'+00:00';

        $this->addVar('article_published', $article_published);

        // задаем крохи
        if(!empty($this->lang->md_breadcrumbs))
            { $brd = $this->lang->md_breadcrumbs; }
        else
            { $brd = $this->lang->md_header; }
        $this->breadcrumbs($brd, $this->data['uri1']);
        $this->breadcrumbs($dataRow['name']);

        $this->addVar('template', $this->render('tmpl/news_view', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
    }

	/**
	* Vuvod limitnogo k-va zapisey
    * @param $offset = 3 - k-vo zapisey
    * @param $template = true - vuvod html
	**/
	function limit($offset = 1)
	{
        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);

        $data['rows'] = (object)array(); // переменная для хранения данных

        $dataRow = $this->$model->limitRow($offset); // вытаскиваем данные
        if($dataRow)
        {
            foreach($dataRow as $key=>$value) // проходим цикл для формирования данных
            {
                //$dates = date('d.m.Y', $value['date']);
                $dates = date('d.m.Y', strtotime($value['date']));
                //date("d",strtotime($_GET['start_date']));
                if($dates == '01.01.1970') { $d = explode('-',$value['date']); $dates = $d[2].'.'.$d[1].'.'.$d[0]; }

                $data['rows']->$key = (object)array();
                $data['rows']->$key->name = $value['name'];
                $data['rows']->$key->text = $value['shorting'];
                $data['rows']->$key->link = BASEURL.'/'.$this->link.'/'.$value['link'];
                $data['rows']->$key->foto = $value['mainfoto'];
                $data['rows']->$key->date = $dates;
            }
        }

        // возвращаем вид
        echo $this->render('tmpl/news_limit', $data);
	}
    function nlimit($type,$num)
    {
        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);
        $data['rows'] = (object)array(); // переменная для хранения данных

        $dataRow = $this->$model->limitRow(2,'',$type); // вытаскиваем данные
        if($dataRow)
        {
            foreach($dataRow as $key=>$value) // проходим цикл для формирования данных
            {

                if($key!=$num){
                    continue;
                }
                //$dates = date('d.m.Y', $value['date']);
                $dates = date('d.m.Y', strtotime($value['date']));
                //date("d",strtotime($_GET['start_date']));
                if($dates == '01.01.1970') { $d = explode('-',$value['date']); $dates = $d[2].'.'.$d[1].'.'.$d[0]; }

                $data['rows']->$key = (object)array();
                $data['rows']->$key->name = $value['name'];
                $data['rows']->$key->text = $value['shorting'];
                $data['rows']->$key->link = BASEURL.'/'.$this->link.'/'.$value['link'];
                $data['rows']->$key->foto = $value['mainfoto'];
                $data['rows']->$key->date = $dates;
            }
        }

        // возвращаем вид
        echo $this->render('tmpl/nnews_limit', $data);
    }

    function limits($offset = 1)
	{
        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);

        $data['rows'] = ''; // переменная для хранения данных

        $dataRow = $this->$model->limitRow($offset); // вытаскиваем данные
        if($dataRow)
        {
            foreach($dataRow as $key=>$value) // проходим цикл для формирования данных
            {
            //$dates = date('d.m.Y', $value['date']);
                $dates = date('d.m.Y', strtotime($value['date']));
                //date("d",strtotime($_GET['start_date']));
                if($dates == '01.01.1970') { $d = explode('-',$value['date']); $dates = $d[2].'.'.$d[1].'.'.$d[0]; }

                $data['rows']->$key->name = $value['name'];
                $data['rows']->$key->text = $value['shorting'];
                $data['rows']->$key->link = BASEURL.'/'.$this->link.'/'.$value['link'];
                $data['rows']->$key->foto = $value['mainfoto'];
                $data['rows']->$key->date =$dates;
            }
        }

        // возвращаем вид
        echo $this->render('tmpl/news_limits', $data);
	}

    /** Пагинация на ajax */
    function ajaxPagination()
	{
        $offset = $this->input->post('page',true); // какую страницу грузить, номер страницы
        $uri2 = $this->input->post('uri2',true);
        $uri3 = $this->input->post('uri3',true);
        $uri4 = $this->input->post('uri4',true);
        $uri5 = $this->input->post('uri5',true);

        /** код для вытаскивания данных */

        // переменная шаблона
        $ok = '';

        // что-то делаем со всеми данными, которые в $_POST
        echo json_encode(array(
            'ok' => $ok
        ));
	}

    /** Отзыв */
    function ajaxComment()
    {
        $name = $this->input->post('name', true);
        $text = nl2br(htmlspecialchars($this->input->post('text', true)));
        $id = $this->input->post('id', true);
        $return = true; // выполнять ли запрос в базу
        $usedCaptcha = false; // использовать ли каптчу

        if($usedCaptcha)
        {
            $captcha = $this->input->post('captcha', true);
            if($captcha == $this->session->userdata('captcha_num')) // проверка каптчи
                { $return = true; }
            else
                { $return = false; $ok = 'errorCaptcha'; }
        }

        if($return)
        {
            $model = $this->table.'_model';
            $this->load->model($this->module.'/'.$model, $model);

            $array = array(
                'tovar_id' => $id,
                'name' => $name,
                'short_text' => $text,
                'date' => date('Y-m-d')
            );
            if($this->$model->module_add($array))
                { $ok = 'success'; }
            else
                { $ok = 'failure'; }
        }

        echo json_encode(array( 'ok' => $ok ));
    }

    /** Поиск */
    function searchFunction($search = '')
    {
        if(empty($search))
        {
            $search = $this->input->post('search_', true);
            $this->session->set_userdata('search_', $search);
            redirect('/'.$this->link.'/search/'.$search);
        }

        $search = $this->session->userdata('search_');

        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);

        $this->breadcrumbs('Поиск');

        /** данные */

        // переменная шаблона
        $this->addVar('template', $this->render('default', $data));
        $this->viewPage($this->data);
     }


}
/* End of file */
