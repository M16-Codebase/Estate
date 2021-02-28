<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class SiteMap extends MY_Controller {


    public function index() {
        $this->load->model('SeoSiteMap', 'sm');
        $this->sm->generate();

        $this->compare();
    }

    public function compare()
    {
        global $h;
        $this->load->model('SeoSiteMap', 'sm');

        $csm = FCPATH . 'current_sitemap.xml';
        $osm = FCPATH . 'sitemap.xml';

        $xml = simplexml_load_file($osm);
        $json = json_encode($xml);
        $osa = json_decode($json,TRUE);
        $osa = $osa['url'];

        $xml = simplexml_load_file($csm);
        $json = json_encode($xml);
        $csa = json_decode($json,TRUE);

        $csa = $csa['url'];

        $delta = [];
/*
        foreach ($osa as $osi) {
            $ia = in_array($osi, $csa);
            if (!$ia) {
                $delta[] = $osi['loc'];
            }
        }*/

        foreach ($csa as $osi) {
            $ia = in_array($osi, $osa);
            if (!$ia) {
                $delta[] = $osi['loc'];
            }
        }
        //$h->debug($delta);


        if (!empty($delta)) {
            $file = fopen(FCPATH . 'delta.xml', 'w');
            fputs($file, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL);
            fputs($file, '<delta>' . PHP_EOL);

            foreach ($delta as $d) {
                fputs($file, '<url>' . $d . '</url>' . PHP_EOL);
            }

            fputs($file, '</delta>' . PHP_EOL);
        }

    }

    public function parse()
    {
        $url = "https://m16-estate.ru/";

        $content = file_get_contents($url);

        $this->_parse($content);
    }

    private function _parse()
    {

    }


}


/*SELECT ci_buildings.id, ci_metro.name
FROM `ci_buildings`
LEFT JOIN ci_metro_buildings ON ci_metro_buildings.building_id = ci_buildings.id
LEFT JOIN ci_metro ON ci_buildings.metro_id=ci_metro.id

WHERE ci_buildings.`razdelu`=0 AND ci_buildings.banned=0*/
