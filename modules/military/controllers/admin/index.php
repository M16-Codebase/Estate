<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*
* Модуль pages | Админ
* Страницы
*
**/


class Index extends MY_Admin
{
    private $needRoute = false; // нужно ли использовать роутер

	function __construct()
	{
		// конструктор
		parent::__construct();

        include(MDPATH.'military/moduleinfo.php');
        $this->module = $moduleinfo['name'];
	}

	/*function res()
    {
        $return = $this->db
            ->select('id, name, price')
            ->where('razdelu', 1)
            ->where('price > ', 0)
            ->where('price <= ', 10000000)
            ->where('banned', 0)
            ->order_by('price', 'asc')
            ->get('buildings')
            ->result();

        echo 'Найдно ' . count($return) . ' объектов';

        echo "<div class='with-padding'><pre class='prettyprint'>\n";
        print_r($return);
        echo '</pre></div>';

        if(!empty($return[0]->id)) {
            $id = [];
            foreach ($return as $item) {
                $id[$item->id] = $item->id;
            }

            echo '<style>html{overflow-y: scroll !important;}</style>';
            echo "<div class='with-padding'><pre class='prettyprint'>\n";
            print_r($id);
            echo '</pre></div>';

            $this->db
                ->where_in('id', $id)
                ->update('buildings', [
                    'military' => 1,
                    'military_banned' => 0
                ]);
        }

    }*/

	function index($all = '')
	{
		$return_data = $this->db
			->where('military', 1)
			->get('buildings')
			->result_array();

		$listData['table'] = '';
		if(!empty($return_data)) {
			foreach($return_data as $val) {

				$ch = 'checked';
				if($val['military_banned'] == 1) {
					$ch = '';
				}

				$dani = "
                    <input type=\"checkbox\" id=\"switch_{$val['id']}\" class=\"switch switches mini wide\" data-id=\"{$val['id']}\" value=\"\" {$ch}>
                    <span style='display: none;'>{$val['military_banned']}</span>
                ";

                $sort = "
                            <input style=\"width: 100%;\" value=\"{$val['military_sort']}\" data-action=\"military_sort\" data-id=\"{$val['id']}\" class=\"sorte\" />
                            <span style='display: none;'>{$val['id']}</span>
                        ";

        		$listData['table'][] = array(
					'name' => '<a target="_blank" title="Открыть запись в новом окне" href="/buildings/admin/index/edit/' . $val['id'] . '">' . $val['name'] . '</a>',
					'price' => $val['price'],
                    'sort' => $sort,
                    'banned' => $dani
				);
			}
		}

		$listData['headingTable'] = array(
            'Название' => array(
                'width'=>'',
                'class'=>'',
                'title' => ''
                ),
            'Цена' => array(
                'width'=>'10%',
                'class'=>'',
                'title' => ''
            ),
            'Приоритет' => array(
                'width'=>'10%',
                'class'=>'',
                'title' => ''
            ),
            'Видимость' => array(
                'width'=>'5%',
                'class'=>'checkbox-cell',
                'title' => '<span class="icon-eye underline with-tooltip blue" title="'.$this->alang->admin_visibility.'"></span>'
                ),
        );

        $listData['uri'] = $this->uri->uri_string();
        $listData['moduleName'] = $this->module; // передаем модуль
        $listData['information'] = '';
        $listData['captionTable'] = 'Военная ипотека';
        $listData['alang'] = $alang;

        $return = $this->admin_view('list_table', $listData, true);

        if(!$this->input->is_ajax_request())
        {
            $this->admin_display($return);
        }
        else
        {
			echo json_encode(array(
				'ok' => $this->admin_view('list_table_ajax', $listData, true)
			));
        }
	}

    /**
     * Управление настройками модуля
     **/
    function config()
    {
        $conf = $this->load->config('military/military', true);

        $data['table'] = '';

        $data['table']['perPaging'] = array(
            'name' => 'perPaging',
            'id' => 'perPaging',
            'type' => 'input',
            'label' => 'К-во записей',
            'value' => $conf['perPaging'],
            'params' => '',
            'info' => 'Количество отображаемых записей на странице',
            'placeholder' => ''
        );

        if($this->input->post('dani'))
        {
            $mas = $this->parseJson($this->input->post('dani'));

            foreach($mas as $item=>$value)
            {
                $in_array[] = $item;
                $data['table'][$item]['value'] = $value;
            }

            $mas['Paging'] = 1;

            if($this->edit_tableInfo(array('configs' => serialize($data)))) // заносим настройки в базу
            {
                $return = configSaveToFile($this->module, $mas); // сохранение настроек в конфигурационный файл
            }
            else
            {
                $return = notification('Настройки не сохранены.', 'red');
            }

            echo json_encode(array(
                'ok' => $return
            ));
        }
        else
        {
            $data['moduleName'] = $this->module;
            echo $this->admin_view('admin/config_table', $data);
        }
    }

    function configDataLoadBD()
    {
        $this->db->select('configs');
        $this->db->where('table', 'ci_'.$this->table);
        $query = $this->db->get('table_info');
        $data = $query->row_array();

        return unserialize($data['configs']);
    }

    public function edit_tableInfo($array)
    {
        $this->db->where('table', 'ci_'.$this->table);
        $query = $this->db->update('table_info', $array);

        return $query;
    }

    function parseJson($data)
    {
        if(is_array($data))
        {
            foreach($data as $d)
            {
                if($d['name'] == 'foto[]')
                {
                    $foto[] = $d['value'];
                }
                elseif($d['name'] == 'foto_alt[]')
                {
                    $alt[] = $d['value'];
                }
                elseif($d['name'] == 'recommend')
                {
                    $rec[] = $d['value'];
                }
                else
                {
                    $sds[$d['name']] = $d['value'];
                }
            }

            if(isset($foto)) {
                $serailize = array(
                    'foto' => $foto,
                    'alt' => $alt
                );
                $sds['foto'] = serialize($serailize);
            }

            if(isset($rec))
            {
                $sds['recommend'] = serialize($rec);
            }
        }
        else
        {
            parse_str($data,$sended);

            foreach($sended as $key=>$sd)
            {
                $ks = str_replace(';','',$key);
                $sds[$ks] = $sd;
            }
        }
        return $sds;
    }

}