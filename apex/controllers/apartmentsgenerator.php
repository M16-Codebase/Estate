<?php


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 10.12.17
 * Time: 11:20
 */

class ApartmentsGenerator extends MY_Controller
{

    private $buildings = [];

    const SITE_ROOT_PATH = FCPATH;



    public function index()
    {
        $get = $this->input->get();
        $id_params = $get['ids'];



        $ids = [];
        $buildings = $this->db->select('id, name, korpus_value')
            ->where_in('id', $id_params)
            ->where(['banned' => 0, 'razdelu' => 0])
            ->get('buildings')->result_array();

        foreach ($buildings as $buiilding) {
            $ids[] = $buiilding['id'];
        }

        $apartments = $this->db->where_in('novostroy_id', $ids)
            ->where('banned', '0')
            ->select('id, novostroy_id, room_id, price, square_all, square_cook, square_life,
                square_corridor,
                main_foto, floor, otdelka_id')
            ->group_by('square_all')
            ->get('apartments')
            ->result_array();

        $rooms = $this->db->get('room')->result_array();

        $result = [
            'buildings' => $buildings,
            'apartments' => $apartments,
            'rooms' => $rooms
        ];

        $this->output->set_content_type('application/json');
        $this->output->set_output(serialize($result));
        //dump(serialize($result));

    }


    public function buildings()
    {
        $buildings = $this->db->select('id, name')
            ->where(['banned' => 0, 'razdelu' => 0])
            ->get('buildings')->result_array();

        echo serialize($buildings);
    }



    private function start()
    {
        echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;

    }
}
