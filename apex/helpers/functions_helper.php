<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** ********************************************************* USERS ******************************************************** **/
if (!function_exists('memSize')) {
	function memSize($size)
    {
        $filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
        return $size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) .$filesizename[$i] : '0 Bytes';
    }
}

if (!function_exists('getPath'))
{
    function getPath($path,$subPath=NULL)
    {
        $path = dirname($path);
        return str_replace(FCPATH.APPPATH,"../",$path).$subPath;
    }
}

if (!function_exists('pf'))
{
    function pf($var = NULL, $name = false)
    {
        echo "<pre>";
        if ($name) echo '<strong class="fred">'.$name.'</strong>'."\r\n";
        print_r($var);
        echo "</pre>";
    }
}

// 
function get_chapters()
{
    return array(
        0 => array('ru' => 'Новостройки',        'en' => 'buildings'),
        1 => array('ru' => 'Вторичная',          'en' => 'resale'),
        2 => array('ru' => 'Загородная',         'en' => 'residential'),
        3 => array('ru' => 'Элитная',            'en' => 'elite'),
        4 => array('ru' => 'Коммерческая',       'en' => 'commercial'),
        5 => array('ru' => 'world',              'en' => 'world'),
        6 => array('ru' => 'Земельные массивы',  'en' => 'land'),
        7 => array('ru' => 'Коттеджные поселки', 'en' => 'abroad'),
        8 => array('ru' => 'Переуступки',        'en' => 'assignment'),
        9 => array('ru' => 'Эксклюзивная',       'en' => 'exclusive'),
    );
}

// Уведомление об удалении
function remove_notify($message = '', $name = '')
{
	if ($name != '')
		$message = $name.':<br /><br />'.$message;

	$CI =& get_instance();
	$CI->load->library('phpmailer'); //Класс phpmailer
	$CI->phpmailer->ClearAllRecipients();
	// Кодировка
	$CI->phpmailer->CharSet = "utf-8";

	$CI->phpmailer->SMTPDebug = 1;
	$CI->phpmailer->CharSet = 'UTF-8';
	$CI->phpmailer->IsSMTP();
	$CI->phpmailer->Host = 'smtp.yandex.ru';
	$CI->phpmailer->Port = 25;
	$CI->phpmailer->SMTPSecure = 'tls';
	$CI->phpmailer->SMTPAuth = true;
	$CI->phpmailer->Username = 'm16.noreplay@yandex.ru';
	$CI->phpmailer->Password = 'Vfkfattd016';
	$config = &get_config(); // получаем конфиги
	//Емайл получателя
	// $this->phpmailer->AddAddress($config['apex_email'],$config['apex_names']); //Мой адрес
	
	$CI->phpmailer->AddAddress('vhel747@gmail.com');
	$CI->phpmailer->AddAddress('banik812@yandex.ru');
    $CI->phpmailer->AddAddress('natalia@art.su');
    /**
     * fix crash on email send. Email ban reason
     * @author Tomansru <stas@tomans.ru>
     */
	// $CI->phpmailer->AddAddress('gilfanova.l@art.su');
	$CI->phpmailer->AddAddress('kazakov@art.su');

	$CI->phpmailer->SetFrom('m16.noreplay@yandex.ru', 'M-16');
	$CI->phpmailer->ContentType = 'text/html';
	$CI->phpmailer->Subject	= $name.' M16-ESTATE.RU';
	$CI->phpmailer->Body	= $message;
	$CI->phpmailer->MsgHTML	= $message;
	
	return $CI->phpmailer->send(); // return true; else return false;
}

function get_remove_text($buildingId = 0, $table = '')
{
	$CI =& get_instance();
	$query = $CI->db->where('id', $buildingId)->get($table);

	$text = array();
	
	if ($query->num_rows())
	{
		$chapters = get_chapters();

		foreach ($query->result_array() as $row)
		{
			$text[] = ($row['title'] != '')?$row['title']:$row['name'];

			if ($table == 'buildings')
			{
				foreach (explode(',', $row['razdelu']) as $num)
					$text[] = 'http://www.m16-estate.ru/'.$chapters[$num]['en'].'/'.$row['link'];
			}
			else
			{
				$text[] = 'http://www.m16-estate.ru/'.$table.'/'.$row['link'];
			}

			$text[] = '------------------------------';
		}

		return implode('<br />', $text);
	}

	return '';
}

function getYoutubeVideoID($link, $desc)
{
	$ci = &get_instance();

    preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtube/)[^&\n]+#", $link, $matches);

    $video = !empty($matches[0]) ? $matches[0] : '';
	$return = '';

	if(!empty($video) || !empty($desc)) {
    	$return = $ci->load->view('themes/default/tmpl/youtube_block_video', array(
			'video' => $video,
			'desc' => $desc
		), true);
	}

	return trim($return);
}

function getYoutubeVideoID_database( $view_name = 'youtube_block_video' )
{
	$ci = &get_instance();

	$ci->db->where('link', uri(2));
	$query = $ci->db->get('buildings');
	$res = $query->row();

    $link = @$res->video_code;
    $desc = @$res->video_desc;
    $return = $video = '';

	if(!empty($link)) {
	    preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtube/)[^&\n]+#", $link, $matches);
	    $video = !empty($matches[0]) ? $matches[0] : '';
	}

	if(!empty($video) || !empty($desc)) {
    	$return = $ci->load->view('themes/default/tmpl/' . $view_name, array(
			'video' => $video,
			'desc' => $desc
		), true);
	}

	return trim($return);
}

// Выводим консультанта
function fetch_consultant($view_name = 'consultant_block_view')
{
    $ci = &get_instance();
    $return = '';

    $table = 'buildings';
    if(uri(1) == 'abroad') {
        $table = 'abroad';
    }

    $get = $ci->db
        ->limit(1)
        ->where('link', uri(2))
        ->get($table)
        ->row();

    if(!empty($get->consultant_id)) {
        $get_consultant = $ci->db
            ->limit(1)
            ->where('banned', 0)
            ->where('id', $get->consultant_id)
            ->get('consultants')
            ->row();

        if(!empty($get_consultant->id)) {
            $return = $ci->load->view('themes/default/tmpl/' . $view_name, $get_consultant, true);
        }
    }

    return $return;
}


/**
 ***********************************
 * Вывод вручную шорткода в html шаблоне 
 ***********************************
**/
function shortcodes($name)
{
   $ci = &get_instance();
   $shortcode = $ci->load->config('shortcode', TRUE); // виджеты | ключ замены => значение   
      
   $langPrefix = config_item('language_prefix'); // префикс языка       
   $langPrefix = $langPrefix == 'ru' ? '' : '_'.$langPrefix; // передаем префикс      
   
   $name = str_replace(array('[',']'), array('',''), $name);
       
   return htmlspecialchars_decode($shortcode[$name.$langPrefix]);    
}

function shortcodes_db($name)
{
	$ci = &get_instance();

	$get = $ci->db
		->select('text')
		->where('name', $name)
		->get('shortcode')
		->row();

	return isset($get->text) ? $get->text : '';
}

function get_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
        

/**
 ***********************************
 * Вывод текста в html шаблоне | возвращаем объект переменных
 ***********************************  
**/
function langLine($line = '')
{
   $ci = &get_instance();
   if(empty($line))
   {
       $return = (object)array();
       $mas = $ci->lang->language;       
       foreach($mas as $key=>$lng)
       {
            $return->$key = html_entity_decode($lng['name']);
       }    
   }
   else
   {
        $l = $ci->lang->line($line);       
        $return = html_entity_decode($l['name']);
   }
   
   return $return;
}


/**
 ***********************************
 * Вытаскиваем параметр из сесии
 * item - название переменной сесии
 ***********************************  
**/
function ses_data($item)
{
    $ci = &get_instance();
    
    return $ci->session->userdata($item);
}


/**
 ***********************************
 * Вывод количествао товара из сесии 
 ***********************************  
**/
function count_item_cart()
    {
        $ci = &get_instance();
        
        $count=0;
        
        if($ci->session->userdata('cart'))
        {
            $car = $ci->session->userdata('cart');
            
            foreach($car as $k=>$c)
            {
                $count += count($car[$k]);
            }
        }
        return $count;
    }	 


/**
 ***********************************
 * Vutaskivaem opredelennuy URI segment
 ***********************************  
**/ 
if ( ! function_exists('uri'))
{
    function uri($int=2)
    {
        $ci = &get_instance();
                 
        return $ci->uri->segment($int);
    }
}  
    

/** --------------------------------------------------------------------------------------------- **/

    
/**
 * Функция отправки сообщения
 * @param $template массив с данными шаблона
 * @param $array массив с данными 
 * @param $sendAdmin письмо будет отправлено администратору, false - от администратора
**/
function sendMessage($template, $array, $sendAdmin = true)
{   
    $ci = &get_instance(); 
    $config = &get_config(); // получаем конфиги
    
    $arraySearch = array(
        '{name}', '{email}', '{phone}', '{header}', '{url}', '{site}', '{text}', '{price}', '{tema}', '{link}'
    );

    if(!empty($array['name'])) { $aName = $array['name']; } else { $aName = $config['apex_names']; }
    if(!empty($array['email'])) { $aEmail = $array['email']; } else { $aEmail = $config['apex_email']; }
    if(!empty($array['phone'])) { $aPhone = $array['phone']; } else { $aPhone = ' - '; }
    if(!empty($array['text'])) { $aText = $array['text']; } else { $aText = ' - '; }
    if(!empty($array['price'])) { $aPrice = $array['price']; } else { $aPrice = ' - '; }
    if(!empty($array['tema'])) { $aTema = $array['tema']; } else { $aTema = ' - '; }
    if(!empty($array['link'])) { $aLink= $array['link']; } else { $aLink = ''; }
    if(!empty($array['h1'])) { $aHeader= $array['h1']; } else { $aHeader = ''; }    
    
    $aUrl = '<a href="'.$array['url'].'">Страница с которой было отправлено письмо</a>';
    $aSite = '<a href="'.site_url().'">'.$config['apex_names'].'</a>';
    
    $arrayReplace = array(
        $aName, $aEmail, $aPhone, $aHeader, $aUrl, $aSite, $aText, $aPrice, $aTema, $aLink
    );

    $templateMessage = str_replace($arraySearch, $arrayReplace, $template->text);
    
    preg_match_all('#<img[^>]+src\s*=\s*(["\'])([^\s"\']+)\\1#im', $templateMessage, $imgurls);
    $afind = array();
    $areplce = array();
    foreach($imgurls[2] as $img)
    {
        $afind[] = $img;
        $areplce[] = site_url($img);
    }
    
    $templateMessage = str_replace($afind, $areplce, $templateMessage);
          
    $template = '
        <style> a { color: #c51230; } a:hover { color: #f68f1f; } </style>
        <div style="width: 100%; margin: 0; padding: 0;  color: #000; font-size:15px; line-height: 135%; font-family: \'Arial\'">
        <div style="max-width: 600px; margin: 0 auto; ">
            '.$templateMessage.'
        </div>        
        </div>
    ';
    
    // массив с данными для отправки письма
    $send = array(
        'name' => $array['name'],
        'email' => $array['email'],
        'tema' => $template->tema,
        'message' => $template
    );
         			
	$ci->load->library('phpmailer'); // Класс phpmailer
	
    // Очистка отправителей и получателей
    $ci->phpmailer->ClearAllRecipients();
    $ci->phpmailer->ClearAddresses();
        
    $ci->phpmailer->CharSet = "utf-8"; // Кодировка  
    $ci->phpmailer->ContentType = 'text/html'; //Тип письма			
	$ci->phpmailer->Subject = $send['tema']; // Тема
    
    $ci->phpmailer->Body = $send['message']; // Сообщение
    $ci->phpmailer->MsgHTML = $send['message']; // Сообщение        
            
    if($sendAdmin)
    {
        $ci->phpmailer->AddAddress($config['apex_email'], $config['apex_names']); // Получатель
    	// Отправитель
    	$ci->phpmailer->From = $send['email'];
    	$ci->phpmailer->FromName = $send['name'];
    } 
    else
    {
        $ci->phpmailer->AddAddress($send['email'], $send['name']); // Получатель
    	// Отправитель
    	$ci->phpmailer->From = $config['apex_email'];
    	$ci->phpmailer->FromName = $config['apex_names'];
    }
	
    //Отправляем
	return $ci->phpmailer->send();				
}


/**
* Календарь (вывод нужных месяцев и года)
* @param $month - с какого месяца будет выводиться календарь
* @param $year - год
* @param $visible - к-во отображаемых месяцев
*/
function createCalendar($month, $year, $visible = 6)
{        
    $month -= 1;  
    
    if($month == 0)
    {
        $month = 12;
        $year -= 1;
    }
              
    $max = 12;    
    $arrayMonth = array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
    
    for($month; $month<=$max; $month++)
    {
      if($visible != 0)
      {    
        $array[$month] = array(
            'month' => $arrayMonth[$month-1],
            'year' => $year
        );
        if($month == $max) { $month = 0; $year += 1; }
      }
      else
      {
        break;
      }
      $visible--;  
    }
    
    return $array;
}

/**
|==========================================================
| название месяца
* 
* $month - месяц
* $s - язык:
*  = 0 - русский
*  = 1 - украинский
*  = 2 - английский
*  = 3 - немецки
|==========================================================
*/
function get_months($month, $s = 'russian'){
  if($month > 12 || $month < 1) return FALSE;
  
  if($s == 'russian')
  {
    $aMonth = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
  }
  elseif($s == 'ukraine')
  {
    $aMonth = array('січня', 'лютого', 'березня', 'квітня', 'травня', 'червня', 'липня', 'серпня','вересня','жовтня','листопада','грудня');
  }
  elseif($s == 'english')
  {
    $aMonth = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
  }
  elseif($s == 'deutsch')
  {
    $aMonth = array('Januar', 'Februar', 'Marz', 'April', 'konnte', 'June', 'Juli', 'August', 'September', 'October', 'November', 'December');
  }
  return $aMonth[$month-1];
}


function get_month_ruen($month, $s = 'ru'){
  if($month > 12 || $month < 1) return FALSE;
  
  if($s == 'ru')
  {
    $aMonth = array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');
  }
  elseif($s == 'en')
  {
    $aMonth = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
  }
  return $aMonth[$month-1];
}

function get_quarter_num($date)
{
    return (int)(((int)date('n', $date) + 2)/3);
}

/**
|==========================================================
| день недели
* 
* $day - день
* $s - язык:
*  = 0 - русский
*  = 1 - украинский
*  = 2 - английский
*  = 3 - немецки
|==========================================================
*/
function get_days($day, $s = 'russian')
{    
    if($day > 6) return FALSE;
    if($s == 'russian')
    {
        switch($day)
        {
            case 0: $d = 'Воскресенье'; break;
            case 1: $d = 'Понедельник'; break;
            case 2: $d = 'Вторник'; break;
            case 3: $d = 'Среда'; break;
            case 4: $d = 'Четверг'; break;
            case 5: $d = 'Пятница'; break;
            case 6: $d = 'Суббота'; break;
        }
    }
    elseif($s == 'ukraine')
    {
        switch($day)
        {
            case 0: $d = 'Неділя'; break;
            case 1: $d = 'Понеділок'; break;
            case 2: $d = 'Вівторок'; break;
            case 3: $d = 'Середа'; break;
            case 4: $d = 'Четвер'; break;
            case 5: $d = 'П`ятниця'; break;
            case 6: $d = 'Субота'; break;
        }
    }
    elseif($s == 'english')
    {
        switch($day)
        {
            case 0: $d = 'Sunday'; break;
            case 1: $d = 'Mondey'; break;
            case 2: $d = 'Tuesday'; break;
            case 3: $d = 'Wednesday'; break;
            case 4: $d = 'Thursday'; break;
            case 5: $d = 'Freeday'; break;
            case 6: $d = 'Saturday'; break;
        }
    }
    elseif($s == 'deutsch')
    {
        switch($day)
        {
            case 0: $d = 'Sonntag'; break;
            case 1: $d = 'Montag'; break;
            case 2: $d = 'Dienstag'; break;
            case 3: $d = 'Mittwoch'; break;
            case 4: $d = 'Donnerstag'; break;
            case 5: $d = 'Freitag'; break;
            case 6: $d = 'Samstag'; break;
        }
    }
    
    return $d; // Возвращаем день
} 

/**
 * Сколько чего осталось
**/
function textDayOfDate($skolko, $chego)
{
    $array = array(
        "",
        "день",
        "дня",
        "дней",
        "неделя",
        "недели",
        "недель",
        "месяц",
        "месяца",
        "месяцев",
        "год",
        "года",
        "лет"
    );

    if ($skolko == 0)
        $skolko = 10;
    if ($skolko == 1)
        $a = 3 * $chego + 1;
    if ($skolko >= 2 && $skolko <= 4)
        $a = 3 * $chego + 2;
    if ($skolko >= 5 && $skolko <= 20)
        $a = 3 * $chego + 3;
    if ($skolko > 20 && $skolko < 100)
        return textDayOfDate($skolko % 10, $chego);
    if ($skolko >= 100)
        return textDayOfDate($skolko % 100, $chego);
    return $array[$a];
}

/**
 * Сколько прошло периода от заданной даты
**/
function DayOfDate( $date, $current = NULL )
{
    if($current == NULL) { $current = date("Y-m-d"); }
    
    $date_old  = explode("-", $date); // дата от которой идет отсчете
    $date_cr  = explode("-", $current); // до какой даты
    
    $std   = mktime(0, 0, 0, $date_old[1], $date_old[2], $date_old[0]);
    $ed    = mktime(0, 0, 0, $date_cr[1], $date_cr[2], $date_cr[0]);
    $e     = abs($ed - $std);
    $f     = date('j-n-Y', $e);
    $dat   = explode("-", $f);
    $day   = $dat[0] - 1;
    $month = $dat[1] - 1;
    $year  = $dat[2] - 1970;
    $week  = 0;
    $soob  = '';
    
    if( $day == 0 ) $day = 1;
    if( $day >= 7 ) { $week = ceil($day / 7); }
    
    
    $soob .= $year.' '.textDayOfDate($year,3);
    $soob .= ' '.$month.' '.textDayOfDate($month,2);
    if($day >= 7) { 
        $soob .= ' '.$week.' '.textDayOfDate($week,1);
    }
    if($week == 0) {    
        $soob .= ' '.$day.' '.textDayOfDate($day,0);
    }
                
    return $soob;
}

/**
 * Определяем реальный IP
**/
function getRealIpAddr()
{
  if (!empty($_SERVER['HTTP_CLIENT_IP']))
  {
    $ip=$_SERVER['HTTP_CLIENT_IP'];
  }
  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
  {
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
  }
  else
  {
    $ip=$_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

/**
 * Проверка на админа
**/
function is_admins()
{
	$ci = &get_instance();
    if($ci->session->userdata('DX_role_name') == 'super' or $ci->session->userdata('DX_role_name') == 'admin')
    {
        return true;
    }        
    else
        return false;
}

function floor_ap($a)
{
    $end = '';

    if(!empty($a)) {
        sort($a);

        foreach ($a as $item) {
            $array[$item] = $item;
        }

        $min = min($a);
        $max = max($a);

        $group = [];
        $group[$min] = $min;

        for ($i = $min; $i <= $max; $i++) {
            if (isset($array[$i])) {
                if (isset($array[$i + 1])) {
                    if ($group[$i] != $min) {
                        $group[] = '-';
                    }
                } else {
                    $group[$i] = $i;
                }
            } else {
                if (isset($array[($i + 1)])) {
                    $group[$i + 1] = $i + 1;
                }
            }
        }

        $next = true;
        foreach ($group as $k => $g) {
            if ($g != '-') {
                $end .= $g;
                if ($g != $max) {
                    if ($group[$k + 1] != '-') {
                        $end .= ', ';
                    }
                }
                $next = true;
            } else {
                if ($next) {
                    $end .= $g;

                }

                $next = false;
            }
        }
    }

    return $end;
}

/**
 * Уникальный ID генератор
 * (к-во символов, префикс)
**/
function myUniqId($numStr, $strPrx = '') 
{ 
    srand((double)microtime()*rand(1000000,9999999)); // Seed random number generator 
    $arrChar = array(); // New array 
    $uId = $strPrx; // Write prefix in the uniq id 
    
    /*for($i=65; $i<90; $i++) 
    { 
        array_push($arrChar, chr($i)); // Add A-Z to array 
        array_push($arrChar, strtolower(chr($i))); // Add a-z to array 
    } */
    
    for($i=48; $i<57; $i++) 
    { 
        array_push($arrChar, chr($i)); // Add 0-9 to array 
    } 
    
    for($i=0; $i<$numStr; $i++) 
    { 
        $uId.=$arrChar[rand(0, count($arrChar))]; // Write random picked chars in the uniq id 
    } 
    
    return $uId; // Print uniq ID on the screen 
}

/**
 * Определение iPhone
**/
function is_iphone()
{
    $return = false;
    
    if(strstr($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'], 'iPod'))
    {
        $return = true;
    }

    return $return;    
}

/**
 * Определение iPad
**/
function is_ipad()
{
    $return = false;
    
    $isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'iPad');
    if($isiPad)
    {
        $return = true;
    }

    return $return;    
}

/**
 * 
**/
function rundomArrayRow()
{
    
}


/**
 * MailChimp подписка 
 * $name - имя фамилия
 * $email - мыло
**/
function mailchimp($name, $email, $list = '6286c208e6')
{
    $ci = &get_instance();
    
    $ci->load->library('Mcapi');    	
        
    $arr['name'] = $name;
    $arr['email'] = $email;			
	$name = explode(' ', $arr['name']);
	
    if(empty($name[1])) { $name[1] = ''; }
    
	return $ci->mcapi->listSubscribe($list, $arr['email'], array('FNAME' => $name[0], 'LNAME' => $name[1]));
}



/**
 * Сумма прописью
 */
function num2str($inn, $stripkop=false) {

    $nol = 'ноль';
    $str[100]= array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот', 'восемьсот','девятьсот');
    $str[11] = array('','десять','одиннадцать','двенадцать','тринадцать', 'четырнадцать','пятнадцать','шестнадцать','семнадцать', 'восемнадцать','девятнадцать','двадцать');
    $str[10] = array('','десять','двадцать','тридцать','сорок','пятьдесят', 'шестьдесят','семьдесят','восемьдесят','девяносто');
    $sex = array(
        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),// m
        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять') // f
    );

    $forms = array(
        array('копейка', 'копейки', 'копеек', 1), // 10^-2
        array('рубль', 'рубля', 'рублей',  0), // 10^ 0
        array('тысяча', 'тысячи', 'тысяч', 1), // 10^ 3
        array('миллион', 'миллиона', 'миллионов',  0), // 10^ 6
        array('миллиард', 'миллиарда', 'миллиардов',  0), // 10^ 9
        array('триллион', 'триллиона', 'триллионов',  0), // 10^12
    );
    $out = $tmp = array();

    // Поехали!
    $tmp = explode('.', str_replace(',','.', $inn));
    $rub = number_format($tmp[ 0], 0,'','-');
    if ($rub== 0) $out[] = $nol;
    // нормализация копеек
    $kop = isset($tmp[1]) ? substr(str_pad($tmp[1], 2, '0', STR_PAD_RIGHT), 0,2) : '00';
    $segments = explode('-', $rub);
    $offset = sizeof($segments);
    if ((int)$rub== 0) { // если 0 рублей
        $o[] = $nol;
        $o[] = morph( 0, $forms[1][ 0],$forms[1][1],$forms[1][2]);
    }
    else {
        foreach ($segments as $k=>$lev) {
            $sexi= (int) $forms[$offset][3]; // определяем род
            $ri = (int) $lev; // текущий сегмент
            if ($ri== 0 && $offset>1) {// если сегмент==0 & не последний уровень(там Units)
                $offset--;
                continue;
            }

            // нормализация
            $ri = str_pad($ri, 3, '0', STR_PAD_LEFT);
            // получаем циферки для анализа
            $r1 = (int)substr($ri, 0,1); //первая цифра
            $r2 = (int)substr($ri,1,1); //вторая
            $r3 = (int)substr($ri,2,1); //третья
            $r22= (int)$r2.$r3; //вторая и третья
            // разгребаем порядки
            if ($ri>99) $o[] = $str[100][$r1]; // Сотни
            if ($r22>20) {// >20
                $o[] = $str[10][$r2];
                $o[] = $sex[ $sexi ][$r3];
            }
            else { // <=20
                if ($r22>9) $o[] = $str[11][$r22-9]; // 10-20
                elseif($r22> 0) $o[] = $sex[ $sexi ][$r3]; // 1-9
            }
            // Рубли
            $o[] = morph($ri, $forms[$offset][ 0],$forms[$offset][1],$forms[$offset][2]);
            $offset--;
        }
    }
    // Копейки
    if (!$stripkop) {
        $o[] = $kop;
        $o[] = morph($kop,$forms[ 0][ 0],$forms[ 0][1],$forms[ 0][2]);
    }
    return preg_replace("/\s{2,}/",' ',implode(' ',$o));
}
/**
 * Склоняем словоформу
 */
function morph($n, $f1, $f2, $f5) {

    $n = abs($n) % 100;

    $n1= $n % 10;

    if ($n>10 && $n<20) return $f5;

    if ($n1>1 && $n1<5) return $f2;

    if ($n1==1) return $f1;

    return $f5;
}



/** 
* Parse XML data into an array 
**/ 
function xml2array($text) { 
    $reg_exp = "/<(\w+)([^>]*)>(.*?)<\/\\1>/s"; 
    preg_match_all($reg_exp, $text, $match); 
    
    foreach ($match[1] as $key=>$val) { 
        $index = count($array[$val]); 
        if (strlen($all_args=trim($match[2][$key]))) { 
            preg_match_all("/[\s]*([^\=]+)\=[\"']*(.*?)[\"']+[\s]*/ms",$all_args,$arg); 
            for( $j=0; $j < count($arg[1]); $j++ ) 
                $array[$val][$index]['arg'][ $arg[1][$j] ] = $arg[2][$j]; 
        } 
        $data = trim($match[3][$key]); 
        $array[$val][$index]['data'] = (substr($data,0,9)=='<![CDATA[') || !preg_match($reg_exp,$data) 
        ? preg_replace("/(^(\/\/|\/\*)\s*<!\[CDATA\[\s*|\s*(\/\/|\*\/)\s*\]\]>$)/mi",'',html_entity_decode($data)) 
        : xml2array($data); 
    } 
    
    return $array; 
}


function simpleXMLToArray(SimpleXMLElement $xml, $attributesKey = null, $childrenKey = null, $valueKey = null)
{ 
    if ($childrenKey    && !is_string($childrenKey))    {$childrenKey   = '@children';}
    if ($attributesKey  && !is_string($attributesKey))  {$attributesKey = '@attributes';}
    if ($valueKey       && !is_string($valueKey))       {$valueKey      = '@values';}

    $return = array();
    $name = $xml->getName();
    $_value = trim((string)$xml);
    if(!strlen($_value)){$_value = null;};

    if ($_value !== null)
    {
        if ($valueKey)
        {
            $return[$valueKey] = $_value;
        }
        else
        {
            $return = $_value;
        }
    }

    $children = array();
    $first = true;
    
    foreach ($xml->children() as $elementName => $child)
    {
        $value = simpleXMLToArray($child,$attributesKey, $childrenKey,$valueKey);
        
        if (isset($children[$elementName]))
        {
            if (is_array($children[$elementName]))
            {
                if($first)
                {
                    $temp = $children[$elementName];
                    unset($children[$elementName]);
                    $children[$elementName][] = $temp;
                    $first=false; 
                }

                $children[$elementName][] = $value;
            }
            else
            {
                $children[$elementName] = array($children[$elementName],$value);
            }
        }
        else
        {
            $children[$elementName] = $value;
        }
    }

    if ($children)
    {
        if ($childrenKey)
        {
            $return[$childrenKey] = $children;
        }
        else
        {
            $return = array_merge($return,$children);
        }
    }

    $attributes = array(); 
    
    foreach($xml->attributes() as $name=>$value)
    {
        $attributes[$name] = trim($value); 
    }
    
    if ($attributes)
    {
        if ($attributesKey)
        {
            $return[$attributesKey] = $attributes;
        }
        else
        {
            $return = array_merge($return, $attributes);
        }
    }

    return $return; 
}





/**
	 * Sort a multi-dimensional array by a column in the sub array
	 *
	 * @param array  $arr Array to sort
	 * @param string $col The name of the column to sort by
	 * @param int    $dir The sort directtion SORT_ASC or SORT_DESC
	 *
	 * @return void
	 */
	function array_multi_sort_by_column(&$arr, $col, $dir = SORT_ASC)
	{
		if (empty($col) || !is_array($arr))
		{
			return false;
		}

		$sort_col = array();
		foreach ($arr as $key => $row) {
			$sort_col[$key] = $row[$col];
		}

		array_multisort($sort_col, $dir, $arr);
	}//end array_multi_sort_by_column()
    
    
    
/**
 * Вывод миниатюры
*/
function thumbImage($foto, $module = '')
{
    if($foto != '/uploads/_thumbs/images/no_image.jpg')
    {
        $k1 = str_replace('images/', '_thumbs/Images/', $foto);
        $k2 = str_replace('images/', '_thumbs/images/', $foto);
        if(is_file($k1))
        { $return = $k1; }
        elseif(is_file($k2))
        { $return = $k2; }
        else { $return = $foto; }
    }
    else
    {
        $return = $foto;
    }

    return $return;
}

/**
 * Конверт размера
*/
function formatSize($size) {
    $filesizename = array(
        " Bytes", " KB", " MB", " GB", " TB",
        " PB", " EB", " ZB", " YB"
    );
    return $size
        ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i]
        : '0 ' . $filesizename[0];
}

/**
 * Обрезка цены
**/
function priceCut($price)
{
    if(!empty($price))
    {
        $st = mb_strlen($price);

        if($st >= 8)
        {
            $p = $st - 4;
            $cut = mb_strcut($price,0,$p);
        }
        elseif($st >= 7)
        {
            $p = $st - 4;
            $cut = mb_strcut($price,0,$p);
        }
        else
        {
            $cut = mb_strcut($price,0,2);
        }

        $count = mb_strlen($cut);

        if($count == 2)
        {
            $return = ''.$cut;
        }
        elseif($count == 1)
        {
            $return = ''.$cut;
        }
        else
        {
            $pos = $count - 2;
            $first = mb_strcut($price,0,$pos);
            $return = $first.''.mb_strcut($price,$pos,3);
        }
    }
    else
    {
        $return = '';
    }

    return $return;
}

function priceCuts($price)
{
    if(!empty($price))
    {
        $st = mb_strlen($price);

        if($st == 6) { $return = '0.'.mb_strcut($price,0,1); }
        elseif($st == 7) { $return = mb_strcut($price,0,1).','.mb_strcut($price,1,1); }
        elseif($st == 8) { $return = mb_strcut($price,0,2).','.mb_strcut($price,2,1); }
        elseif($st == 9) { $return = mb_strcut($price,0,3).','.mb_strcut($price,3,1); }
    }
    else
    {
        $return = '';
    }

    return $return;
}

function toTranslitUrl($to_url) {
    $trans = array('А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
        'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I',
        'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
        'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
        'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'Ch',
        'Ш' => 'Sh', 'Щ' => 'Sch', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '',
        'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',

        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
        'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
        'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
        'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
        'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya');

    $url = strtr($to_url, $trans);    // Заменяем кириллицу согласно массиву замены
    $url = mb_strtolower($url);	      // В нижний регистр

    $url = preg_replace("/[^a-z0-9-,\s]/i", "", $url);  // Удаляем лишние символы
    $url = preg_replace("/[,-]/ui", " ", $url);         // Заменяем на пробелы
    $url = preg_replace('|\s+|', '-', $url);         // Заменяем 1 и более пробелов на "-"

    return $url;
}



if (function_exists('js') === FALSE)
{
    function js($file, $base_url = TRUE, $params = array())
    {
        
        $ci = & get_instance();

        if ($base_url != FALSE)
        {
            if (function_exists('base_url') === FALSE)
            {
                $ci->load->helper('url');
            }

            $file = base_url().ltrim($file, '/');
        }

        if (isset($ci->config->config['js'][$file]) === FALSE)
        {
            $ci->config->config['js'][$file] = $params;
        }
        else
        {
            $ci->config->config['js'][$file] = array_merge(
                $params,
                $ci->config->config['js'][$file]
            );
        }
        
        
    }
} 