<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('memory_limit', '4096M');
class yandex_turbo extends MX_Controller {
    private $category = [];
    private $link;
    private $date;
    private $yandex_turbo_allowed_tags = '<p><a><h1><h2><h3><br><figure><img><figcaption><header><ul><ol><li><video><source>';
    const NEWS = 'news';

    public function index() {
        ini_set('memory_limit', '4096M');
        if(strpos('|'.$_SERVER['REQUEST_URI'],'turbo/buildings/')){
            $off=explode('/',$_SERVER['REQUEST_URI']);
            $off=(int)$off[3]-1;
            $this->render_turbo_buildings(100,100*$off);
        }elseif(strpos('|'.$_SERVER['REQUEST_URI'],'turbo/news/')){
            $off=explode('/',$_SERVER['REQUEST_URI']);
            $off=(int)$off[3]-1;
            $this->render_turbo_news(500,500*$off);
        }else{
            $this->render_turbo_buildings(5,0);
        }
    }

    public function render_turbo_buildings($limit=5,$offset=0) {
        $pages = $this->prepareLabels($limit,$offset);
        $items = '';
        if(count($pages)===0){
            show_404('404: Нет у нас столько турбо-страниц!');
        }else {
            foreach ($pages as $page) {
                $items .= $this->prepareItem($page['link']);
            }
            header('Content-Type: application/rss+xml; charset=UTF-8');
            $full = '';
            $full .= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
            $full .= '<rss 
                xmlns:yandex="http://news.yandex.ru" 
                xmlns:media="http://search.yahoo.com/mrss/"  
                xmlns:turbo="http://turbo.yandex.ru" 
                version="2.0">' . PHP_EOL;
            $full .= '<channel>' . PHP_EOL;
            $full .= '<title>M16-Недвижимость</title>' . PHP_EOL;
            $full .= '<link>https://m16-estate.ru</link>' . PHP_EOL;
            $full .= '<description>
                    Продажа недвижимости в Санкт-Петербурге и Ленинградской области! ✔ Лучшие предложения на рынке жилой, загородной и коммерческой недвижимости на официальном сайте агентства Вячеслава Малафеева «М16-Недвижимость»!
                </description>' . PHP_EOL;
            $full .= '<language>ru</language>' . PHP_EOL;
            $full .= $items . PHP_EOL;
            $full .= '</channel>' . PHP_EOL;
            $full .= '</rss>' . PHP_EOL;
            echo $full;
        }
    }
    public function prepareItem($label) {
        return '<item turbo="true">
            ' . str_replace('&','&amp;',$this->prepareTitle($label)) . '
            ' . $this->prepareLink($label) . '
            ' . $this->prepareContent($label) . '
        </item>';
    }
    public function prepareTitle($label) {
        $rows = $this->sqlGet('ci_buildings', '`title`', "`link`='" . $label . "'");
        return '<title>' . $rows[0]['title'] . '</title>';
    }
    public function prepareLabels($limit,$offset) {
        return $this->sqlGet('ci_buildings', '`link`', '`razdelu`=0 AND `banned`=0',$limit,$offset);
    }
    public function prepareLink($label) {
        return '<link>https://m16-estate.ru/buildings/' . $label . '</link>';
    }
    public function prepareContent($label) {
        $dataRow = array();
        $dataRow['header'] = $this->prepHeader($label);
        $dataRow['slider'] = $this->prepSlider($label);
        $dataRow['rating'] = $this->prepRating();
        $dataRow['video'] = $this->prepVideo($label);
        $dataRow['params'] = $this->prepParams($label);
        $dataRow['apartments'] = $this->prepApart($label);
        $dataRow['same'] = $this->prepSame($label);
        $dataRow['callback'] = $this->prepCall();
        return '
            <turbo:content>
                <![CDATA[
                    ' . $dataRow['header'] . $dataRow['slider'] . $dataRow['rating'] . $dataRow['video'] . $dataRow['params'] . $dataRow['apartments'] . $dataRow['same'] . $dataRow['callback'] . '
                ]]>
            </turbo:content>
            ';
    }
    public function prepHeader($label) {
        $dataRows=array();
        $rows = $this->sqlGet('ci_buildings', '`name`', "`link`='" . $label . "'");
        foreach ($rows as $value) {
            $dataRows['h1'] = '<h1>' . $value['name'] . '</h1>';
        }
        $rows = $this->sqlGet('ci_buildings', '`price`', "`link`='" . $label . "'");
        foreach ($rows as $value) {
            $dataRows['h2'] = '<h2>от ' . number_format(((int)$value['price'] / 1000000), 2, '.', ' ') . ' млн руб</h2>';
        }
        $rows = $this->sqlGet('ci_buildings', '`bigfoto`', "`link`='" . $label . "'");
        foreach ($rows as $value) {
            $dataRows['img'] = $value['bigfoto'];
        }
        return '<header>' . $dataRows['h1'] . $dataRows['h2'] . $dataRows['img'] . '</header>';
    }
    public function prepVideo($label) {
        $videolink = $this->sqlGet('ci_buildings', '`video_code`', "`link`='" . $label . "'");
        if (isset($videolink['video_code'])) {
            $videolink = str_replace('https://www.youtube.com/watch?v=', '', $videolink['video_code']);
            $data = '
		    <iframe
                width="640"
                height="360"
                src=
                 "https://www.youtube.com/embed/' . $videolink . '"
                allowfullscreen>
           </iframe>
		';
        } else {
            $data = '';
        }
        return $data;
    }
    public function prepSlider($label) {
        $srd = $this->sqlGet('ci_buildings', '`foto`', "`link`='" . $label . "'");
        $links=unserialize($srd[0]['foto'])['foto'];
        foreach ($links  as $key=>$link){
            if(strpos($link,'crm')){
                unset($links[$key]);
            }
        }
        $links=array_values($links);
        if(count($links)>0) {
            $data = '<div data-block="slider">';
            foreach ($links as $key=>$link) {
                if($key>2){
                    break;
                }
                $data .= '<figure><img src="'.$this->checkImg( $link ).'" /></figure>'.PHP_EOL;
            }
            $data .= '</div>';
            return $data;
        }
        $srd = $this->sqlGet('ci_buildings', '`mainfoto`', "`link`='" . $label . "'");
        return '
        <div data-block="slider">
        <figure><img src="https://m16-estate.ru' . $srd[0]['mainfoto'] . '" /></figure>
        </div>
        ';
    }
    public function prepRating() {
        $sr = $this->sqlGet('ci_buildings', '`rating`,`raters`', '`razdelu`=0');
        $data = (int)(($sr[0]['rating']) / ($sr[0]['raters']));
        return '
            <div itemscope itemtype="http://schema.org/Rating">
                <meta itemprop="ratingValue" content="' . $data . '">
                <meta itemprop="bestRating" content="5">
            </div>';
    }
    public function prepParams($label) {
        $dataRows = $this->sqlGet('ci_buildings', '`area`,`adress`,`metro_id`,`rayon_id`,`korpus_value`,`builder_id`,`xml_text`,`text`,`otdelka`,`infrastruct`', "`link`='" . $label . "'");
        $data_s = '';
        if (strlen($dataRows[0]['xml_text']) > 20) {
            $data_s.= '
		<div data-block="item" data-title="Описание">
			' . $dataRows[0]['xml_text'] . '
		</div>
		';
        } else {
            $data_s.= '
		<div data-block="item" data-title="Описание">
			' . $dataRows[0]['text'] . '
		</div>
		';
        }
        if (strlen($dataRows[0]['otdelka']) > 20) {
            $data_s.= '
		<div data-block="item" data-title="Отделка квартир">
			' . $dataRows[0]['otdelka'] . '
		</div>
		';
        }
        if (strlen($dataRows[0]['infrastruct']) > 20) {
            $data_s.= '
		<div data-block="item" data-title="Инфраструктура">
			' . $dataRows[0]['infrastruct'] . '
		</div>
		';
        }
        return '
		<table data-invisible="true">
		   <tr>
			  <!--Заголовок таблицы-->
			  <th><img src="https://m16-estate.ru/asset/assets/img/srocico.png"></th>
			  <th>Срок сдачи: ' . $this->dataKv($dataRows[0]['korpus_value']) . '</th>
		   </tr>
		   <tr>
			  <!--Строка таблицы-->
			  <td><img src="https://m16-estate.ru/asset/assets/img/placeico.png"></td>
			  <td>' . $dataRows[0]['area'] . ', ' . $dataRows[0]['adress'] . '</td>
		   </tr>
		   <tr>
			  <!--Строка таблицы-->
			  <td><img src="https://m16-estate.ru/asset/assets/img/metroico.png"></td>
			  <td>' . $this->metro($dataRows[0]['metro_id']) . '</td>
		   </tr>
		   <tr>
			  <!--Строка таблицы-->
			  <td>Район:</td>
			  <td>' . $this->rayon($dataRows[0]['rayon_id']) . '</td>
		   </tr>
		   <tr>
			  <!--Строка таблицы-->
			  <td>Застройщик:</td>
			  <td>' . $this->builder($dataRows[0]['builder_id']) . '</td>
		   </tr>
		</table>
		<div data-block="accordion">
			' . $data_s . '
		</div>	
		';
    }
    public function prepApart($label) {
        $id = $this->sqlGet('ci_buildings', '`id`', "`link`='" . $label . "'");
        $id = $id[0]['id'];
        $dataRow[] = $this->sqlGet('ci_apartments', '`price`,`square_all`,`main_foto`,`room_id`,`id`', "`novostroy_id`='" . $id . "'");
        /*echo'<pre>';
        print_r($dataRow);
        echo'</pre>';
        exit;
        */
        if (count($dataRow[0]) > 0) {
            $rooms = array();
            $rooms['std'][0] = 0;
            $rooms['1k'][0] = 0;
            $rooms['2k'][0] = 0;
            $rooms['3k'][0] = 0;
            $rooms['4k'][0] = 0;
            $rooms['5k'][0] = 0;
            $rooms['6k'][0] = 0;
            $rooms['7k'][0] = 0;
            $rooms['2kk'][0] = 0;
            $rooms['3kk'][0] = 0;
            $rooms['4kk'][0] = 0;
            foreach ($dataRow[0] as $key => $value) {
                if ($value['room_id'] === '1') {
                    $rooms['std'][0] += 1;
                    $rooms['std'][1][] = $key;
                }
                if ($value['room_id'] === '2') {
                    $rooms['1k'][0] += 1;
                    $rooms['1k'][1][] = $key;
                }
                if ($value['room_id'] === '3') {
                    $rooms['2k'][0] += 1;
                    $rooms['2k'][1][] = $key;
                }
                if ($value['room_id'] === '4') {
                    $rooms['3k'][0] += 1;
                    $rooms['3k'][1][] = $key;
                }
                if ($value['room_id'] === '5') {
                    $rooms['4k'][0] += 1;
                    $rooms['4k'][1][] = $key;
                }
                if ($value['room_id'] === '6') {
                    $rooms['5k'][0] += 1;
                    $rooms['5k'][1][] = $key;
                }
                if ($value['room_id'] === '7') {
                    $rooms['6k'][0] += 1;
                    $rooms['6k'][1][] = $key;
                }
                if ($value['room_id'] === '16') {
                    $rooms['2kk'][0] += 1;
                    $rooms['2kk'][1][] = $key;
                }
                if ($value['room_id'] === '17') {
                    $rooms['3kk'][0] += 1;
                    $rooms['3kk'][1][] = $key;
                }
                if ($value['room_id'] === '18') {
                    $rooms['4kk'][0] += 1;
                    $rooms['4kk'][1][] = $key;
                }
            }
            $data = '';
            foreach ($rooms as $key=>$r_id){
                if($r_id[0]>0){
                    $data.= '
                    <div data-block="item" data-title="'.$this->kvTypeToStr($key).'" data-expanded="false">
                        <table data-invisible="true">
                            <tr><!--Заголовок таблицы-->
                                <th>Площадь</th>
                                <th>Стоимость квартиры</th>
                                <th>Планировка</th>
                            </tr>
                    ';
                    $ct=0;
                    foreach ($r_id[1] as $value) {
                        if ($ct > 3) {
                            break;
                        }
                        $data.= '
                            <tr><!--Строка таблицы-->
                                <td>' . $dataRow[0][$value]['square_all'] . '</td>
                                <td>от ' . substr((string)number_format($dataRow[0][$value]['price'], 2, ' ', ' '), 0, -3) . ' руб.</td>
                                <td>
                                    <img class="imgwidth" src="' . $this->checkImg($dataRow[0][$value]['main_foto']) . '">
                                </td>
                            </tr>
                        ';
                        $ct++;
                    }
                    $data.= '</table></div>';
                }
            }
            if (strlen($data) > 1) {
                return '
                <h2>Планировки</h2>
                <div data-block="accordion">
                ' . $data . '
                </div>
                <button
               formaction="https://m16-estate.ru/buildings/' . $label . '"
               data-background-color="#019cdf"
               data-color="white"
               data-primary="true">Посмотреть все планировки</button>
                ';
            }
            return '';
        }
        return '';
    }

    public function checkImg($path){
        if(file_exists($_SERVER['DOCUMENT_ROOT'].$path)) {
            return 'https://m16-estate.ru'.$path;
        }
        return 'https://m16-estate.ru/asset/img/logo-M16.png';
    }

    public function kvTypeToStr($type){
        switch ($type){
            case 'std':
                return 'Студии';
                break;
            case '1k':
                return '1-комнатные';
                break;
            case '2k':
                return '2-комнатные';
                break;
            case '3k':
                return '3-комнатные';
                break;
            case '4k':
                return '4-комнатные';
                break;
            case '5k':
                return '5-комнатные';
                break;
            case '6k':
                return '6-комнатные';
                break;
            case '2kk':
                return '2-комнатные (евро)';
                break;
            case '3kk':
                return '3-комнатные (евро)';
                break;
            case '4kk':
                return '4-комнатные (евро)';
                break;
            default:
                return 'Квартиры';
                break;
        }
    }

    public function prepSame($label) {
        $price = $this->sqlGet('ci_buildings', '`price`', "`link`='" . $label . "'");
        $price = $price[0]['price'];
        if ($price < 3000000) {
            $dataRows[] = $this->sqlGet('ci_buildings', '`name`,`link`,`price`,`mainfoto`', "`price`<3000000 AND `link`!='$label'");
        } elseif ($price < 5000000) {
            $dataRows[] = $this->sqlGet('ci_buildings', '`name`,`link`,`price`,`mainfoto`', "`price`>3000000 AND `price`<5000000 AND `link`!='$label'");
        } elseif ($price < 15000000) {
            $dataRows[] = $this->sqlGet('ci_buildings', '`name`,`link`,`price`,`mainfoto`', "`price`>5000000 AND `price`<15000000 AND `link`!='$label'");
        } else {
            $dataRows[] = $this->sqlGet('ci_buildings', '`name`,`link`,`price`,`mainfoto`', "`price`>15000000 AND `link`!='$label'");
        }
        return '
            <h2 class="h2">Объекты рядом</h2>
            <div data-block="slider">
                <figure>
                    <figcaption><a href="https://m16-estate.ru/buildings/' . $dataRows[0][0]['link'] . '">' . $dataRows[0][0]['name'] . ' от ' . ((int)$dataRows[0][0]['price'] / 1000000) . ' млн руб</a></figcaption>
                    <a href="https://m16-estate.ru/buildings/' . $dataRows[0][0]['link'] . '"><img src="' . $dataRows[0][0]['mainfoto'] . '"></a>
                </figure>
                <figure>
                    <figcaption><a href="https://m16-estate.ru/buildings/' . $dataRows[0][1]['link'] . '">' . $dataRows[0][1]['name'] . ' от ' . ((int)$dataRows[0][1]['price'] / 1000000) . ' млн руб</a></figcaption>
                    <a href="https://m16-estate.ru/buildings/' . $dataRows[0][1]['link'] . '"><img src="' . $dataRows[0][1]['mainfoto'] . '"></a>
                </figure>
            </div>
        ';
    }
    public function prepCall() {
        return '
            <form
                data-type="callback"
                data-background-color="#019cdf"
                data-color="white"
                data-primary="true"
                data-send-to="mail@m16-estate.ru"
            </form>
        ';
    }
    public function sqlGet($table, $subject, $where,$limit=30,$offset=0) {
        $mysqli = new mysqli('localhost', 'ned', '5M2i1R0q', 'ned');
        if ($mysqli->connect_error) {
            die('Ошибка подключения (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
        }
        $mysqli->query("SET NAMES 'utf8mb4'");
        $result = array();
        //echo'<br>';
        if ($data = $mysqli->query('SELECT ' . $subject . ' FROM ' . $table . ' WHERE ' . $where . ' LIMIT '.$limit .' OFFSET '.$offset)) {
            if (($data->num_rows) === 1) {
                $row = $data->fetch_array(MYSQLI_ASSOC);
                $result[] = $row;
                return $result;
            }
            if (($data->num_rows) === 0) {
                return null;
            }
            while ($row = $data->fetch_assoc()) {
                $result[] = $row;
            }
            return $result;
        }
        $result[] = $mysqli->error;
        return $result;
    }
    public function metro($id) {
        $data = $this->sqlGet('ci_metro', '`name`', "`id`='" . $id . "'");
        return $data[0]['name'];
    }
    public function rayon($id) {
        $data = $this->sqlGet('ci_rayon', '`name`', "`id`='" . $id . "'");
        return $data[0]['name'];
    }
    public function builder($id) {
        $data = $this->sqlGet('ci_builders', '`name`', "`id`='" . $id . "'");
        return $data[0]['name'];
    }
    public function dataKv($dt, $mini = true) {
        $newTime = strtotime(date('d.m.Y H:i:s'));
        $r='';
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

    public function render_turbo_news($limit1,$offset){
        $this->date = new DateTime;
        $this->link = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        $this->db->select(['id', 'name']);
        $query = $this->db->get('ncategory');
        $nCats = $query->result_array();
        foreach ($nCats as $nCat) {
            $this->category[$nCat['id']] = $nCat['name'];
        }
        $idsArr = [];
        $where = '';
        if (!empty($idsArr)) {
            $where = "id IN (" . implode(', ', $idsArr) . ")";
        }
        $news = $this->getNews($where,$limit1,$offset);
        $this->generateHeader();
        foreach ($news as $new) {
            $this->addItem($new);
        }
        $this->closeRss();
        exit;
    }
    private function getNews($where, $limit,$offset)
    {
        $select = 'name, description, link, date, text, ncategory';
        $this->db->select($select);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->where(['banned'=>0]);
        $this->db->limit($limit,$offset);
        $query = $this->db->get(self::NEWS);
        return $query->result_array();
    }

    private function addItem($item){
        $title = strtolower($item['name']);
        $description = addslashes(strtolower($item['description']));
        echo "<item turbo=\"true\">" . PHP_EOL;
        echo "<title>".str_replace('&','&amp;',$title)."</title>" . PHP_EOL;
        echo "<description>".str_replace('&','&amp;',$description)."</description>" . PHP_EOL;
        echo "<link>{$this->link}/news/{$item['link']}</link>" . PHP_EOL;
        $date = explode('-', $item['date']);
        date_date_set ($this->date, $date[0], $date[1], $date[2]);
        date_time_set ($this->date, 0, 0);
        echo "<pubDate>{$this->date->format(DateTime::RFC822)}</pubDate>" . PHP_EOL;
        $content = $this->content($item);
        echo "<turbo:content>{$content}</turbo:content>" . PHP_EOL;
        echo "</item>" . PHP_EOL;
    }

    private function content($item)
    {
        if (!empty($item)) {
            $content = "<header><h1>{$item['name']}</h1></header>";
            $content .= $item['text'];
            $content = strip_tags( $content, $this->yandex_turbo_allowed_tags );

            $content = preg_replace('/class\s*=\s*".*?"/', '', $content );
            $content = preg_replace('/class\s*=\s*\'.*?\'/', '', $content );
            $content = preg_replace('/\s+>/', '>', $content );
            $content = str_replace('src="/uploads','src="/asset/uploads', $content );
            $content = mb_ereg_replace('\n', '</p><p>', $content);

            $content = $this->wrapBrtag($content);
            $content = $this->wrapImages($content);
            $content = $this->cdata( $content );
            return $content;
        }
        return '';
    }
    function wrapBrtag($content){
        $content = preg_replace('<br />', '/p><p', $content );
        $content = preg_replace('/<p[^>]*><\\/p[^>]*>/', '', $content );
        preg_match_all('!(<br.*>)!Ui', $content, $matches);
        if(isset($matches[1]) && !empty($matches)){
            foreach($matches[1] as $k => $v) {
                $content = str_replace($v, "<p>&nbsp;</p>", $content);
            }
        }
        return $content;
    }

    private function generateHeader()
    {
        $this->openRss();
    }

    private function openRss()
    {
        header('Content-Type: application/rss+xml; charset=UTF-8');
        echo '<rss 
                xmlns:yandex="http://news.yandex.ru" 
                xmlns:media="http://search.yahoo.com/mrss/"  
                xmlns:turbo="http://turbo.yandex.ru" 
                version="2.0">' . PHP_EOL .
            '<channel>' . PHP_EOL;

        echo '<title>M16-Estate</title>' . PHP_EOL;
        echo "<link>{$this->link}</link>" . PHP_EOL;
        echo '<description></description>' . PHP_EOL;
        echo '<language>ru</language>' . PHP_EOL;
    }

    private function closeRss()
    {
        echo '</channel>' . PHP_EOL;
        echo '</rss>' . PHP_EOL;
    }

    private function cdata( $content )
    {
        $content = '<![CDATA[' . str_replace( ']]>', ']]]]><![CDATA[>', $content ) . ']]>';
        return $content;
    }

    private function wrapImages($content)
    {
        preg_match_all('!(<img.*>)!Ui', $content, $matches);
        if(isset($matches[1]) && !empty($matches)){
            foreach($matches[1] as $k => $v) {
                if(!preg_match('!<figure>.*?'. preg_quote($v).'.*?</figure>!is', $content)) {
                    $content = str_replace($v, "<figure>{$v}</figure>", $content);
                }
            }
        }
        return $content;
    }
}
