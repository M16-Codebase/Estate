<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class parser extends MY_Controller
{
    function __construct()
    {
        // конструктор
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }

    function index()
    {
        $this->load->view('parser');
    }

    function do_upload()
    {
        $config['upload_path'] = './asset/files/';
		$config['allowed_types'] = 'txt';
		$config['max_size']	= '50000';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());

			$this->load->view('parser_result', $error);
		}
		else
		{
		    $upl = $this->upload->data();

			$data = array(
                'upload_data' => $upl,
                'folder' => $this->input->post('folder'),
                'catalog' =>$this->input->post('catalog'),
                'files' => $upl['file_name']
            );

            if(!empty($data['upload_data']['file_name']))
            {
                echo 'Файл загружен. <br>Запущена обработка объектов...';
            }
			$this->load->view('parser_result', $data);
		}

    }

    function download_image($imgpath, $dirname)
    {
      //$imgpath = $_POST['img_url']; // - абсолютная ссылка на загрузку изображения (берется из POST-параметра макроса PHP_SCRIPT)
      //$dirname = $_POST['img_folder']; // - имя папки изображений (берется из POST - параметра, указывать без слеша на конце)
      $imgid = ''; // - если нужно, чтобы к имени каждой картинки подставлялся свой id, то передавайте POST-параметр imgid

      $ch = curl_init($imgpath);
      $fn = ($imgid.substr($imgpath,strrpos($imgpath,'/')+1,strlen($imgpath)));
      $fn = str_replace('?', '_', $fn);
      $fn = str_replace('=', '_', $fn);
      if(!file_exists($dirname)) { mkdir($dirname); }
      $fp = fopen($dirname.'/'.$fn, 'wb');
      curl_setopt($ch, CURLOPT_FILE, $fp);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_exec($ch);
      curl_close($ch);
      fclose($fp);

      return $dirname.'/'.$fn; // - вернет путь картинки, загруженной на сервер
      //echo($fn); // - вернет только имя файла картинки, загруженной на сервер
    }


    function sql_insert()
    {
        set_time_limit(0);
        ini_set('MAX_EXECUTION_TIME', -1);
        date_default_timezone_set('Europe/Kiev');

        include_once(APPPATH.'/libraries/simple_html_dom.php');
        $html = new simple_html_dom();

        $folder = $this->input->post('folder');
        $complex = $this->input->post('catalog');
        $fileName = $this->input->post('files');

        $mp = MDPATH.'../asset/uploads/images/apartments/'.$folder;
        $mp_thumb = MDPATH.'../asset/uploads/_thumbs/Images/apartments/'.$folder;
        $files = MDPATH.'../asset/files/'.$fileName;

        $html->load_file($files);

        $tb = $html->find('tr');

        $num = $html->find('tr .num');

        $tb_mainfoto = $html->find('tr .table_plan_img');
        $tb_thumb = $html->find('tr .table_plan_img img');
        $tb_price = $html->find('tr .price_or');
        $tb_price_diap = $html->find('tr .price_or.diaposon');
        $tb_square = $html->find('tr .all_s');
        $tb_squarerooms = $html->find('tr .rooms_s');
        $tb_squarecook = $html->find('tr .kitchen_s');
        $tb_rooms = $html->find('tr .third');
        $tb_floor = $html->find('tr .fourth');

        $sql = "";
        $i = 0;
        $k = 0;
        $number = '';

        $mainfoto = '';
        foreach($tb as $k=>$t)
        {

            /*if(mb_strlen($tb_price[$k]->plaintext) < 10)
            { */
                $cena = $tb_price[$k]->plaintext;

                $pri = str_replace(' тыс ','',$cena);
                $pri = str_replace('.','',$pri);

                if($pri > 0)
                {
                    $price = trim($pri).'000';
                }
                else
                {
                    $price = '';
                }
            /*}
            else
            {
                $price = '';
            } */

            $square = $tb_square[$k]->plaintext;
            $squarerooms = $tb_squarerooms[$k]->plaintext;
            $squarecook = $tb_squarecook[$k]->plaintext;
            $rooms = $tb_rooms[$k]->plaintext;
            $floor = $tb_floor[$k]->plaintext;

            $mainfoto = $this->download_image($tb_mainfoto[$k]->href, $mp);
            $mainfoto = str_replace('/home/webapexc/verstka/modules/..','',$mainfoto);
            $thumb = $this->download_image($tb_thumb[$k]->src, $mp_thumb); // миниатюра

            $sql = " INSERT INTO `ci_room` (`id_catalog`, `square`, `squareroom`, `squarecook`, `rooms`, `floor`, `price`, `mainfoto`) VALUES (".$complex.", '".$square."', '".$squarerooms."', '".$squarecook."', '".$rooms."', '".$floor."', '".$price."', '".$mainfoto."');";
            if($this->db->simple_query($sql)) { $i++; } else { $number .= $num[$k]->plaintext.'<br>'; }
            $k++;
        }

        echo json_encode(array(
            'ok' => 'Всего объектов найдено в цикле парсера: '.$k.'<br><br>Обработано объектов: '.$i.'<br><br>Не обработаны номера объектов: <br>'.$number.'<hr>'
        ));

    }


}