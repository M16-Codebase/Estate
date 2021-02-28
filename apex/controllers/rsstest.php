<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class RssTest extends MX_Controller
{
    private $category = [];
    private $link;
    private $date;
    private $yandex_turbo_allowed_tags = '<p><a><h1><h2><h3><figure><img><figcaption><header><ul><ol><li><video><source>';
    const COUNT_FILES = 5;
    const MAX_PER_FILE = 500;
    const NEWS = 'news';
    const START = 'start';
    const FINISH = 'finish';
    private $errors = [];

    private $outout;


    function _remap($method, $argument)
    {
        if (!empty($argument)) {
            $part = (int)$argument[0];
            $this->index($part);
        } else
            $this->index();
    }

    public function index($part=null)
    {
        global $h;
        if (!is_null($part) && ($part < 1 || $part > self::COUNT_FILES)) {
            $this->errors[] = 'INVALID_ARGUMENT';
        }
        if (!empty($this->errors)) {
            $this->error();
        }

        $start = microtime(true);
        $this->date = new DateTime;
        $this->link = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];

        $this->db->select(['id', 'name']);
        $query = $this->db->get('ncategory');
        $nCats = $query->result_array();
        foreach ($nCats as $nCat) {
            $this->category[$nCat['id']] = $nCat['name'];
        }

        $countNews = $this->countNews();
        $minId = $this->minId();
        $ids = $this->ids();

        $perFile = intval($countNews / self::COUNT_FILES);
        $rest = intval($countNews % self::COUNT_FILES);

        $pages = [];
        $limit = [];
        $completed = 0;
        $idsArr = [];

        if ($part) {
            for ($i = 1; $i <= self::COUNT_FILES; $i++) {
                if ($i === 1) {
                    $start = 0;
                    $finish = $perFile + $rest;
                    $completed += $finish;
                } else {
                    $start = $completed + 1;
                    $finish = $start + $perFile - 1;
                    $completed += $perFile;
                }
                if ($i === self::COUNT_FILES) {
                    $finish -= 1;
                }
                $pages[$i][self::START] = $start;
                $pages[$i][self::FINISH] = $finish;
            }
            if (isset($pages[$part])) {
                $limit = $pages[$part];
                foreach ($ids as $key=>$id) {
                    if ($key >= $pages[$part][self::START] && $key <= $pages[$part][self::FINISH]) {
                        $idsArr[] = $ids[$key]['id'];
                    }
                }
            }
            dump($rest);
            dump($countNews);
            dump($perFile);
            dump($pages);return;
        }
        $where = '';
        if (!empty($idsArr)) {
            $where = "id IN (" . implode(', ', $idsArr) . ")";
        }
        $news = $this->getNews($where);

        $this->generateHeader();

        foreach ($news as $new) {
            $this->addItem($new);
        }
        $this->closeRss();

        $time = microtime(true) - $start;
        $memory = memSize(memory_get_usage());
        //$h->debug($time, 'time');
        //$h->debug($memory, 'memory');

        exit;
    }

    private function getNews($where, $limit=array())
    {
        $select = 'name, description, link, date, text, ncategory';
        $this->db->select($select);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->where(['banned'=>0]);
        if (!empty($limit) && isset($limit[self::START]) && isset($limit[self::FINISH])) {
            $query = $this->db->get(self::NEWS, $limit[self::START], $limit[self::FINISH]);
        } else {
            $query = $this->db->get(self::NEWS);
        }
        //dump($this->db);
        return $query->result_array();
    }


    private function ids()
    {
        $this->db->select('id');
        $this->db->where(['banned'=>0]);
        $query = $this->db->get(self::NEWS);
        return $query->result_array();
    }

    private function countNews()
    {
        $this->db->select('COUNT(`id`) AS cnt');
        $this->db->where(['banned'=>0]);
        $query = $this->db->get(self::NEWS);
        $countNews = $query->result_array();
        return intval($countNews[0]['cnt']);
    }

    private function minId()
    {
        $this->db->select('MIN(`id`) as min');
        $this->db->where(['banned'=>0]);
        $query = $this->db->get(self::NEWS);
        $minId = $query->result_array();
        return intval($minId[0]['min']);
    }


    private function addItem($item)
    {
        $title = strtolower($item['name']);
        $description = addcslashes(strtolower($item['description']));

        echo "<item turbo=\"true\">" . PHP_EOL;
        echo "<title>{$title}</title>" . PHP_EOL;
        echo "<description>{$description}</description>" . PHP_EOL;
        echo "<link>{$this->link}/news/{$item['link']}</link>" . PHP_EOL;
        //echo "<category>{$this->category[$item['ncategory']]}</category>" . PHP_EOL;

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

            $content = $this->wrapImages($content);
            $content = $this->cdata( $content );
            return $content;
        }
    }

    private function generateHeader()
    {
        $this->openRss();
    }

    private function openRss()
    {
        header('Content-Type: application/rss+xml; charset=UTF-8');
        echo '<?xml version="1.0" encoding="UTF-8"?><rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/"  xmlns:turbo="http://turbo.yandex.ru" version="2.0">' . PHP_EOL .
            '<channel>' . PHP_EOL;

        echo '<title></title>' . PHP_EOL;
        echo "<link>{$this->link}</link>" . PHP_EOL;
        echo '<description></description>' . PHP_EOL;
        echo '<language>ru</language>' . PHP_EOL;
        //echo "<yandex:logo>{$this->link}/asset/assets/img/m16-logo.png</yandex:logo>" . PHP_EOL;
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

    private function error()
    {
        if (!empty($this->errors)) {
            echo json_encode($this->errors);
            exit;
        } else
            return;
    }
}


function dump($var, $info = false) {
      $bt = debug_backtrace();

      echo '<br />';
      echo "========= file : {$bt[0]['file']}, line: {$bt[0]['line']} ==========";
      echo '<pre>';
      var_dump($var);
      echo '</pre>';
      if ($info) {
          foreach ($bt as $b) {
              echo '<small>file : ' .$b['file'].', line: '. $b['line'].'</small>';
              echo '<br />';
          }
      } else
          echo 'file : ' .$bt[1]['file'].', line: '. $bt[1]['line'];
      echo '<br />'; echo '==================='; echo '<br />';
  }
